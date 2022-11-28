<?php
namespace Drupal\ucb_course;

use Drupal\ucb_course\Entity\UCBClassEntity;

class ClassSyncClient{

  protected $app_id = '';
  protected $app_key = '';


  protected $term_id = null;
  protected $subject_area = '';

  protected $page_size = 50;

  protected $records = [];


  protected $base_uri = 'https://apis.berkeley.edu/sis/v1/classes/sections';

  public function __construct($term_id) {
    $config = \Drupal::config('ucb_course.settings');
    $this->base_uri = $config->get('class_base_uri');
    $this->app_id = $config->get('class_api_id');
    $this->app_key = $config->get('class_api_key');
    $this->subject_area = $config->get('subject_area');

    $this->term_id = $term_id;
  }

  public function do_import(){
    foreach($this->records as $record){
      if(isset($record->class) && isset($record->class->course->catalogNumber->formatted)){
        $this->upsert_item($record);
      }
    }
  }

  protected function upsert_item($record){
    $class_entities = \Drupal::entityTypeManager()->getStorage('ucb_class_entity')->loadByProperties(['remote_id' => $record->id]);

    if(empty($class_entities)){
      $class_entity = UCBClassEntity::create([
        'remote_id' => $record->id,
        'name' => $record->class->course->transcriptTitle,       
        'status' => $record->printInScheduleOfClasses,   
      ]);
    }else{
      $class_entity = reset($class_entities);
    }


    // $class_entity->prevent_auto_update
    


    if(isset($record->id)){
      $class_entity->ccn = $record->id;
    }

    if(isset($record->class->course->catalogNumber->number)){
      $class_entity->number = $record->class->course->catalogNumber->number;
    }

    if(isset($record->class->course->catalogNumber->formatted)){
      $class_entity->number_formatted = $record->class->course->catalogNumber->formatted;
    }


    if(isset($record->printInScheduleOfClasses)){
      $class_entity->status = $record->printInScheduleOfClasses;
    }

    if(isset($record->class->course->transcriptTitle)){
      $class_entity->name = $record->class->course->transcriptTitle;
    }

    if(isset($record->displayName)){
      $class_entity->name = $record->displayName;


      $name = str_replace(' ', '-', $record->displayName);
      $name = strtolower($name);
      $class_entity->link = 'http://classes.berkeley.edu/content/'.$name;
    }



    if(isset($record->component->code)){
      $class_entity->section_type = $record->component->code;
    }

    if(isset($record->meetings) && is_array($record->meetings)){
      $meeting = $record->meetings[0];
      
      // location
      if(isset($meeting->location->description)){
        $class_entity->location = $meeting->location->description;
      }



      //times
      if(isset($meeting->meetsDays) && isset($meeting->startTime) && isset($meeting->endTime)) {
        $startTime_d = \DateTime::createFromFormat("H:i:s", $meeting->startTime);
        $startTime = $startTime_d->format("g:ia");
    
        $endTime_d = \DateTime::createFromFormat("H:i:s", $meeting->endTime);
        $endTime = $endTime_d->format("g:ia");

        $meeting_time_string = $meeting->meetsDays.' '.$startTime.' - '.$endTime;
        $class_entity->times = $meeting_time_string;
      }

      if(isset($meeting->assignedInstructors)){
        $class_entity->instructors_payload = json_encode($meeting->assignedInstructors);
      }

      //instructors
      // if(isset($meeting->assignedInstructors)){
      //   $excludedRoles =  ['APRX']; // APRX = 5-PROXY
      //   foreach($meeting->assignedInstructors as $instructor){
      //     if(isset($instructor->role->code) && !in_array($instructor->role->code, $excludedRoles)) {
      //       foreach ($instructor->instructor->names as $name) {
      //         if (isset($name->type) && isset($name->type->code) && $name->type->code == 'PRF' && isset($name->formattedName)) {
      //           $instructors[] = $name->formattedName;
      //         }
      //       }
      //     }
      //   }
      // }
    }


    //association
    if(isset($record->association) && isset($record->association->primaryAssociatedSectionId)){
      $class_entity->associated_section_id = $record->association->primaryAssociatedSectionId;
    }


    // enrollment status
    if(isset($record->enrollmentStatus)){
      $class_entity->enrollment_status = $record->enrollmentStatus->status->code;
      $class_entity->enrollment_enrolled_count = $record->enrollmentStatus->enrolledCount;
      $class_entity->enrollment_reserved_count = $record->enrollmentStatus->reservedCount;
      $class_entity->enrollment_waitlisted_count = $record->enrollmentStatus->waitlistedCount;
      $class_entity->enrollment_min_enroll = $record->enrollmentStatus->minEnroll;
      $class_entity->enrollment_max_enroll = $record->enrollmentStatus->maxEnroll;
      $class_entity->enrollment_max_waitlist = $record->enrollmentStatus->maxWaitlist;
      $class_entity->enrollment_open_reserved = $record->enrollmentStatus->openReserved;

      $class_entity->enrollment_payload = json_encode($record->enrollmentStatus);
    }
   
    if(isset($record->status) && isset($record->status->code)){
      $class_entity->status_code = $record->status->code;
    }

    // get course
    $course_entities = \Drupal::entityTypeManager()->getStorage('ucb_course_entity')->loadByProperties(['remote_id' => $record->class->course->catalogNumber->formatted]);
    if(!empty($course_entities)){
      $course_entity = reset($course_entities);
      $class_entity->course_reference = $course_entity;
  
      if(isset($course_entity->description->value)){
        $class_entity->description = $course_entity->description->value;
      }
  
      if(isset($course_entity->level->value)){
        $class_entity->level = $course_entity->level->value;
      }
  
      if(isset($course_entity->units->value)){
        $class_entity->units = $course_entity->units->value;
      }
    }

    // get term
    $term_entities = \Drupal::entityTypeManager()->getStorage('ucb_term_entity')->loadByProperties(['term_id' => $record->class->session->term->id]);
    if(!empty($term_entities)){
      $term_entity = reset($term_entities);
      $class_entity->term_reference = $term_entity;
    }

    $class_entity->save();
  }


  public function do_fetch(){
    $increment_page = true;
    $page_number = 1;
    while ($increment_page == true){
      $records = $this->fetch_classes($page_number);
      if(is_array($records)){
        $this->records = array_merge($this->records, $records);
      }
      if(count($records) < $this->page_size){
        $increment_page = false;
      }else{
        $page_number = $page_number + 1;
      }
    }
  }

  public function fetch_classes($page_number){
    $uri = $this->base_uri.'?term-id='.$this->term_id.'&subject-area-code='.$this->subject_area.'&page-number='.$page_number.'&page-size='. $this->page_size;

    $response = \Drupal::httpClient()->get($uri, [
      'headers' => [
          'app_id' => $this->app_id,
          'app_key' => $this->app_key,
          'accept' => 'application/json'
      ],
      'http_errors' => false
    ]);

    $code = $response->getStatusCode();
    if ($code == 200) {
      $data = (string) $response->getBody();
      if (!empty($data)){
        $data = json_decode($data);
      }
      if (!empty($data) && !empty($data->apiResponse) && !empty($data->apiResponse->response) && !empty($data->apiResponse->response->classSections)) {
        return $data->apiResponse->response->classSections;
      }
    }else{
      \Drupal::messenger()->addError('API ERROR: '.$response->getBody()->getContents());
    }

    return FALSE;
  }
}
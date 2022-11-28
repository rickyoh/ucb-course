<?php
namespace Drupal\ucb_course;

use Drupal\ucb_course\Entity\UCBCourseEntity;

class CourseSyncClient{
  protected $app_id = '';
  protected $app_key = '';


  protected $subject_area = '';

  protected $page_size = 50;

  protected $records = [];

  protected $base_uri = '';

  public function __construct() {
    $config = \Drupal::config('ucb_course.settings');
    $this->base_uri = $config->get('course_base_uri');
    $this->app_id = $config->get('course_api_id');
    $this->app_key = $config->get('course_api_key');
    $this->subject_area = $config->get('subject_area');
  }

  public function do_import(){
    foreach($this->records as $record){
      if(isset($record->catalogNumber) && isset($record->catalogNumber->formatted)){
        $this->upsert_item($record);
      }
    }
  }

  protected function upsert_item($record){
    $course_entities = \Drupal::entityTypeManager()->getStorage('ucb_course_entity')->loadByProperties(['remote_id' => $record->catalogNumber->formatted]);

    if(empty($course_entities)){
      $course_entity = UCBCourseEntity::create([
        'remote_id' => $record->catalogNumber->formatted,
        'name' => $record->title,       
        'status' => 1,   
      ]);
    }else{
      $course_entity = reset($course_entities);
    }

    if(isset($record->title)){
      $course_entity->name = $record->title;
    }

    if(isset($record->catalogNumber->formatted)){
      $course_entity->number = $record->catalogNumber->formatted;

      if(is_string($course_entity->number)){
        $sorting_number = preg_replace("/[^0-9]/", "", $course_entity->number);
        $sorting_number = str_pad($sorting_number,3,"0",STR_PAD_LEFT);
        $course_entity->sorting_number = $sorting_number;
      }
    }

    if(isset($record->description)){
      $course_entity->description = $record->description;
    }

    if(isset($record->academicCareer->code)){
      $course_entity->level = $record->academicCareer->code;
    }

    if(isset($record->credit->value->fixed)){
      $course_entity->units = $record->credit->value->fixed->units;
    }
    $course_entity->save();
  }

  public function do_fetch(){
    $increment_page = true;
    $page_number = 1;
    while ($increment_page == true){
      $records = $this->fetch_courses($page_number);
      dsm($records);
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

  public function fetch_courses($page_number){
    $uri = $this->base_uri.'?status-code=ACTIVE&subject-area-code='.$this->subject_area.'&page-number='.$page_number.'&page-size='. $this->page_size;
   
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

      if (!empty($data) && !empty($data->apiResponse) && !empty($data->apiResponse->response) && !empty($data->apiResponse->response) && !empty($data->apiResponse->response->courses)) {
        return $data->apiResponse->response->courses;
      }
    }else{
      \Drupal::messenger()->addError('API ERROR: '.$response->getBody()->getContents());
    }

    return FALSE;
  }
}
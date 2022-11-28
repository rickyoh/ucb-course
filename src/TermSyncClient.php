<?php
namespace Drupal\ucb_course;

use Drupal\ucb_course\Entity\UCBTermEntity;

class TermSyncClient{

  protected $app_id = '';
  protected $app_key = '';

  protected $temporal_position = 'Next';
  protected $as_of_date = null;
  protected $base_uri = '';

  protected $records = [];

  public function __construct($date = null) {
    $config = \Drupal::config('ucb_course.settings');
    $this->base_uri = $config->get('term_base_uri');
    $this->app_id = $config->get('term_api_id');
    $this->app_key = $config->get('term_api_key');
    $this->subject_area = $config->get('subject_area');
    $this->as_of_date = $date;
  }

  public function do_import(){

    $terms_codes = ['UGRD', 'GRAD'];

    foreach($this->records as $record){
      if(isset($record->id) && isset($record->academicCareer->code) && in_array($record->academicCareer->code, $terms_codes)){
        $term_entities = \Drupal::entityTypeManager()->getStorage('ucb_term_entity')->loadByProperties(['term_id' => $record->id]);
        if(empty($term_entities)){
          $term_entity = UCBTermEntity::create([
            'name' => $record->name,       
            'term_id' => $record->id,
            'status' => 1,   
           ]);
        }else{
          $term_entity = reset($term_entities);
        }

        $term_entity->begin_date = $record->beginDate;
        $term_entity->end_date = $record->endDate;

        if(isset($term_entity->field_begin_date)){
          $term_entity->field_begin_date = $record->beginDate.'T07:00:00';
        }
        if(isset($term_entity->field_end_date)){
          $term_entity->field_end_date = $record->endDate.'T07:00:00';
        }
        
        $term_entity->academic_year = $record->academicYear;
        $term_entity->save();
      }
    }
  }

  public function do_fetch(){
    $increment_page = true;
    $page_number = 1;
    $this->records = $this->fetch_terms($page_number);
  }

  public function fetch_terms($page_number){
    $uri = $this->base_uri.'?temporal-position='.$this->temporal_position;

    if($this->as_of_date != null){
      $uri = $this->base_uri.'?as-of-date='.$this->as_of_date;
    }
  

    // $uri = $this->base_uri.'?as-of-date=2021-08-15';
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
      \Drupal::messenger()->addStatus('Importing term data from : '.$uri);

      $data = (string) $response->getBody();
      if (!empty($data)){
        $data = json_decode($data);
      }   
      if (!empty($data) && !empty($data->apiResponse) && !empty($data->apiResponse->response) && !empty($data->apiResponse->response->terms)) {
        return $data->apiResponse->response->terms;
      }
    }else{
      \Drupal::messenger()->addError('API ERROR: '.$response->getBody()->getContents());
    }

    return FALSE;
  }
}
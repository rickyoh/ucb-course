<?php

namespace Drupal\ucb_course\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for UCB Term entities.
 */
class UCBTermEntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    
    // issue https://www.drupal.org/node/2489476.
    //$this->attachDateTimeViewsData($data);
    return $data;
  }

  /**
   * Fix views data integration for the datetime field.
   */
  protected function attachDateTimeViewsData(&$data) {
    // Automatic integration blocked behind https://www.drupal.org/node/2489476.
    $datetime_columns = [
      'begin_date',
      'end_date',
    ];

    $table_name = 'ucb_term_entity';
    foreach ($datetime_columns as $datetime_column_name) {
      $data[$table_name][$datetime_column_name]['filter']['id'] = 'datetime';
      $data[$table_name][$datetime_column_name]['filter']['field_name'] = 'date_time';
      $data[$table_name][$datetime_column_name]['argument']['id'] = 'datetime';
      $data[$table_name][$datetime_column_name]['argument']['field_name'] = 'date_time';
      $data[$table_name][$datetime_column_name]['sort']['id'] = 'datetime';
      $data[$table_name][$datetime_column_name]['sort']['field_name'] = 'date_time';
    }

  }

}

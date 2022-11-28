<?php

namespace Drupal\ucb_course\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;
use Drupal\Component\Utility\UrlHelper;

class SettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ucb_course_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['ucb_course.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    global $base_url;

    $config = $this->config('ucb_course.settings');

    $form['subject_area'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Subject Area Code'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('subject_area'),
    ];


    $form['auto_enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Automatic Import Enabled'),
      '#default_value' => $config->get('auto_enabled'),
    ];

    $form['auto_enable_terms'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Automatic Import of Terms'),
      '#default_value' => $config->get('auto_enable_terms'),
    ];

    $form['auto_enable_courses'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Automatic Import of Course Data'),
      '#default_value' => $config->get('auto_enable_courses'),
    ];

    $form['auto_enable_classes'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Automatic Import of Classes'),
      '#default_value' => $config->get('auto_enable_classes'),
    ];

    $form['auto_interval'] = [
      '#type' => 'select',
      '#title' => $this->t('Interval'),
     // '#required' => TRUE,
      '#options' => [10800 => '3 hours', 21600 => '6 hours', 43200=> '12 hours', 86400 =>'1 day'],
      '#default_value' => $config->get('auto_interval'),
    ];

    $query = \Drupal::entityQuery('ucb_term_entity')
    ->condition('status', 1);
    $t_ids = $query->execute();

    $terms = \Drupal\ucb_course\Entity\UCBTermEntity::loadMultiple($t_ids);

    $term_opts = [];
    foreach($terms as $term){
      $term_id = $term->get('term_id')->value;
      $term_opts[$term_id] = $term->getName();
    }


    $form['auto_term'] = [
      '#type' => 'select',
      '#title' => $this->t('Term to automatically import class sections for.'),
   //   '#required' => TRUE,
      '#options' => $term_opts,
      '#default_value' => $config->get('auto_term'),
    ];

    $form['api_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('API credentials'),
    ];

    $form['api_fieldset']['term'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Term API'),
    ];
    $form['api_fieldset']['term']['term_base_uri'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Term API URI'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('term_base_uri'),
    ];
    $form['api_fieldset']['term']['term_api_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Term API ID'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('term_api_id'),
    ];
    $form['api_fieldset']['term']['term_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Term API KEY'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('term_api_key'),
    ];

    $form['api_fieldset']['term']['import'] = [
      '#type' => 'details',
      '#title' => $this->t('Import'),
      '#open' => FALSE, 
    ];

    $form['api_fieldset']['term']['import']['term_import'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Next Term'),
      '#submit' => [
        [$this, 'ucb_term_import_all']
      ],
    ];

    $form['api_fieldset']['term']['import']['term_import_markup'] = [
      '#type' => 'inline_template',
      '#template' => '<div style="margin-top:10px;"><b>OR</b></div>',
    ];

    $now = time();
    $form['api_fieldset']['term']['import']['term_import_range'] = [
      '#type' => 'date',
      '#title' => $this->t('Import Specific Term'),
      '#default_value' => date("Y-m-d", $now),
      '#attributes' => array('type'=> 'date', 'min'=> '-1 years', 'max' => '+5 years' ),
      '#date_format' => "Y-m-d",
      '#date_year_range' => '-1:+5',
    ];

    $form['api_fieldset']['term']['import']['term_import_range_submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Specific Term'),
      '#submit' => [
        [$this, 'ucb_term_import_range']
      ],
    ];

    $form['api_fieldset']['course'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Course API'),
    ];
    $form['api_fieldset']['course']['course_base_uri'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Course API URI'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('course_base_uri'),
    ];
    $form['api_fieldset']['course']['course_api_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Course API ID'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('course_api_id'),
    ];
    $form['api_fieldset']['course']['course_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Course API KEY'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('course_api_key'),
    ];
    $form['api_fieldset']['course']['import'] = [
      '#type' => 'details',
      '#title' => $this->t('Import'),
      '#open' => FALSE, 
    ];

    $form['api_fieldset']['course']['import']['course_import'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Courses'),
      '#submit' => [
        [$this, 'ucb_course_import_all']
      ],
    ];

    $form['api_fieldset']['class'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Class API'),
    ];
    $form['api_fieldset']['class']['class_base_uri'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Class API URI'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('class_base_uri'),
    ];
    $form['api_fieldset']['class']['class_api_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Class API ID'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('class_api_id'),
    ];
    $form['api_fieldset']['class']['class_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Class API KEY'),
      '#required' => FALSE,
      '#size' => 100,
      '#default_value' => $config->get('class_api_key'),
    ];

    $form['api_fieldset']['class']['import'] = [
      '#type' => 'details',
      '#title' => $this->t('Import'),
      '#open' => FALSE, 
    ];

    $term_options = [];
    $term_entities = \Drupal::entityTypeManager()->getStorage('ucb_term_entity')->loadByProperties(['status' => 1]);
    if(!empty($term_entities)){   
      $term_options = [null=>'- Any -'];
      foreach($term_entities as $term_entity){
        $term_options[$term_entity->get('term_id')->value] = $term_entity->getName();
      }
    }

    $form['api_fieldset']['class']['import']['class_select_term'] = [
      '#type' => 'select',
      '#title' => $this->t('Select Term'),
      '#options' => $term_options, 
    ];


    $form['api_fieldset']['class']['import']['class_import'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import Classes for selected term'),
      '#submit' => [
        [$this, 'ucb_class_import_all']
      ],
    ];


    $form = parent::buildForm($form, $form_state);

    $form['actions']['import'] = [
      '#type' => 'submit',
      '#value' => $this->t('Import'),
      '#submit' => [
        [$this, 'ucb_course_import_all']
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('ucb_course.settings')
      ->set('auto_enabled', $form_state->getValue('auto_enabled'))
      ->set('auto_interval', $form_state->getValue('auto_interval'))
      ->set('auto_term', $form_state->getValue('auto_term'))
      ->set('auto_enable_terms', $form_state->getValue('auto_enable_terms'))
      ->set('auto_enable_courses', $form_state->getValue('auto_enable_courses'))
      ->set('auto_enable_classes', $form_state->getValue('auto_enable_classes'))
      ->set('subject_area', $form_state->getValue('subject_area'))
      ->set('term_base_uri', $form_state->getValue('term_base_uri'))
      ->set('term_api_id', $form_state->getValue('term_api_id'))
      ->set('term_api_key', $form_state->getValue('term_api_key'))
      ->set('course_base_uri', $form_state->getValue('course_base_uri'))
      ->set('course_api_id', $form_state->getValue('course_api_id'))
      ->set('course_api_key', $form_state->getValue('course_api_key'))
      ->set('class_base_uri', $form_state->getValue('class_base_uri'))
      ->set('class_api_id', $form_state->getValue('class_api_id'))
      ->set('class_api_key', $form_state->getValue('class_api_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  public function ucb_term_import_all(array &$form, FormStateInterface $form_state) {
    _ucb_course_import_terms();
  }

  public function ucb_term_import_range(array &$form, FormStateInterface $form_state) {
    $term_import_range = $form_state->getValue('term_import_range');
    _ucb_course_import_terms($term_import_range);
  }

  public function ucb_course_import_all(array &$form, FormStateInterface $form_state) {
    _ucb_course_import_courses();
  }

  public function ucb_class_import_all(array &$form, FormStateInterface $form_state) {
    $class_select_term = $form_state->getValue('class_select_term');
    if(empty($class_select_term)){
      \Drupal::messenger()->addStatus('please set a term to import classes');
    }else{
      _ucb_course_import_term_classes($class_select_term);
    }
  }

}

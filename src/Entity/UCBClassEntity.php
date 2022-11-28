<?php

namespace Drupal\ucb_course\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the UCB Class entity.
 *
 * @ingroup ucb_course
 *
 * @ContentEntityType(
 *   id = "ucb_class_entity",
 *   label = @Translation("UCB Class"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ucb_course\UCBClassEntityListBuilder",
 *     "views_data" = "Drupal\ucb_course\Entity\UCBClassEntityViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\ucb_course\Form\UCBClassEntityForm",
 *       "add" = "Drupal\ucb_course\Form\UCBClassEntityForm",
 *       "edit" = "Drupal\ucb_course\Form\UCBClassEntityForm",
 *       "delete" = "Drupal\ucb_course\Form\UCBClassEntityDeleteForm",
 *     },
 *     "access" = "Drupal\ucb_course\UCBClassEntityAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\ucb_course\UCBClassEntityHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "ucb_class_entity",
 *   admin_permission = "administer ucb class entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/ucb_class_entity/{ucb_class_entity}",
 *     "add-form" = "/admin/structure/ucb_class_entity/add",
 *     "edit-form" = "/admin/structure/ucb_class_entity/{ucb_class_entity}/edit",
 *     "delete-form" = "/admin/structure/ucb_class_entity/{ucb_class_entity}/delete",
 *     "collection" = "/admin/structure/ucb_class_entity",
 *   },
 *   field_ui_base_route = "ucb_class_entity.settings"
 * )
 */
class UCBClassEntity extends ContentEntityBase implements UCBClassEntityInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += [
      'user_id' => \Drupal::currentUser()->id(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the UCB Class entity.'))
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the UCB Class entity.'))
      ->setSettings([
        'max_length' => 50,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);


    $fields['link'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Link'))
      ->setDescription(t('Link to the UCB Class on classes.berkeley.edu'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setRequired(TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the UCB Class is published.'))
      ->setDefaultValue(TRUE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['remote_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Remote ID'))
      ->setDescription(t('The unique id of the remote entity.'));

    $fields['prevent_auto_update'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Override - Do not allow automatic update'))
      ->setDescription(t('A boolean indicating whether the UCB Class can no longer by updated from the API.'))
      ->setDefaultValue(FALSE)
      ->setDisplayOptions('form', [
        'type' => 'boolean_checkbox',
        'weight' => -3,
      ]);


    $fields['class_payload'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Class payload'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
      ));

    $fields['term_reference'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Term Reference'))
      ->setDescription(t('Reference to the Term Entity'))
      ->setSetting('target_type', 'ucb_term_entity')
      ->setSetting('handler', 'default:ucb_term_entity')
      ->setSetting('handler_settings', [
        'auto_create' => FALSE,
      ])
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'string',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 2,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => 'Enter term...',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['course_reference'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Course Reference'))
      ->setDescription(t('Reference to the Course Entity'))
      ->setSetting('target_type', 'ucb_course_entity')
      ->setSetting('handler', 'default:ucb_course_entity')
      ->setSetting('handler_settings', [
        'auto_create' => FALSE,
      ])
      ->setRequired(TRUE)
      ->setTranslatable(FALSE)
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'string',
        'weight' => 2,
      ])
      ->setDisplayOptions('form', [
        'type' => 'entity_reference_autocomplete',
        'weight' => 2,
        'settings' => [
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'placeholder' => 'Enter course...',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['ccn'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Course CCN'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['location'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Course Location'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['times'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Course Times'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['section_type'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Section Type'))
      ->setDescription(t(''))
      ->setSettings([
        'allowed_values' => [
          'LEC' => 'Lecture',
          'DIS' => 'Discussion',
          'SEM' => 'Seminar',
          'IND' => 'Independent Study',
          'GRP' => 'Group Study',
          'VOL' => 'Volunteer',
          'LAB' => 'Laboratory',
          'COL' => 'Colloquium',
          'FLD' => 'Field Work',
          'WOR' => 'WOR',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'list_default',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 6,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['level'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Course Level'))
      ->setDescription(t(''))
      ->setSettings([
        'allowed_values' => [
          'UGRD' => 'Undergraduate',
          'GRAD' => 'Graduate',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'list_default',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 6,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['units'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Course Units'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['description'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Course description'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
      ))
      ->setDisplayOptions('form', array(
        'type'   => 'text_textarea',
        'weight' => -6
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);
  
    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Course number - Number Only'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
          'weight' => -6,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);


    $fields['number_formatted'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Course number'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['instructors_payload'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Instructors payload'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
      ));

    $fields['associated_section_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Associated Section Id'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
          'weight' => 4,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

// enrollment

    $fields['enrollment_status'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Enrollment: Status'))
      ->setDescription(t(''))
      ->setSettings([
        'allowed_values' => [
          'O' => 'Open',
          'C' => 'Closed',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'list_default',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 6,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['enrollment_enrolled_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Enrollment: Enrolled Count'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
          'weight' => 4,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    $fields['enrollment_reserved_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Enrollment: Reserved Count'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
          'weight' => 4,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    $fields['enrollment_waitlisted_count'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Enrollment: Waitlisted Count'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
          'weight' => 4,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

      $fields['enrollment_min_enroll'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Enrollment: Minimum'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
          'weight' => 4,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    $fields['enrollment_max_enroll'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Enrollment: Maximum'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
          'weight' => 4,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    $fields['enrollment_max_waitlist'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Enrollment: Max Waitlist'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
          'weight' => 4,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    $fields['enrollment_open_reserved'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Enrollment: Open Reserved'))
      ->setDescription(t(''))
      ->setDisplayOptions('view', array(
          'label' => 'above',
          'weight' => 4,
      ))
      ->setDisplayOptions('form', array(
          'weight' => 4,
      ))
      ->setDisplayConfigurable('form', true)
      ->setDisplayConfigurable('view', true);

    $fields['enrollment_payload'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Enrollment: Payload'))
      ->setDescription(t(''))
      ->setSettings(array(
        'default_value' => '',
      ));

    
    $fields['status_code'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Status Code'))
      ->setDescription(t(''))
      ->setSettings([
        'allowed_values' => [
          'A' => 'Active',
          'X' => 'Cancelled Section',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'visible',
        'type' => 'list_default',
        'weight' => 6,
      ])
      ->setDisplayOptions('form', [
        'type' => 'options_select',
        'weight' => 6,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);
      
    return $fields;
  }

}

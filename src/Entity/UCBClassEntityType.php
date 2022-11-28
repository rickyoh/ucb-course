<?php

namespace Drupal\ucb_course\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the UCB Class type entity.
 *
 * @ConfigEntityType(
 *   id = "ucb_class_entity_type",
 *   label = @Translation("UCB Class type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\ucb_course\UCBClassEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\ucb_course\Form\UCBClassEntityTypeForm",
 *       "edit" = "Drupal\ucb_course\Form\UCBClassEntityTypeForm",
 *       "delete" = "Drupal\ucb_course\Form\UCBClassEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\ucb_course\UCBClassEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "ucb_class_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "ucb_class_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/ucb_class_entity_type/{ucb_class_entity_type}",
 *     "add-form" = "/admin/structure/ucb_class_entity_type/add",
 *     "edit-form" = "/admin/structure/ucb_class_entity_type/{ucb_class_entity_type}/edit",
 *     "delete-form" = "/admin/structure/ucb_class_entity_type/{ucb_class_entity_type}/delete",
 *     "collection" = "/admin/structure/ucb_class_entity_type"
 *   }
 * )
 */
class UCBClassEntityType extends ConfigEntityBundleBase implements UCBClassEntityTypeInterface {

  /**
   * The UCB Class type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The UCB Class type label.
   *
   * @var string
   */
  protected $label;

}

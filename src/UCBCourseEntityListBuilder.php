<?php

namespace Drupal\ucb_course;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of UCB Course entities.
 *
 * @ingroup ucb_course
 */
class UCBCourseEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('UCB Course ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\ucb_course\Entity\UCBCourseEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.ucb_course_entity.edit_form',
      ['ucb_course_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}

<?php

namespace Drupal\ucb_course;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of UCB Class entities.
 *
 * @ingroup ucb_course
 */
class UCBClassEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('UCB Class ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\ucb_course\Entity\UCBClassEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.ucb_class_entity.edit_form',
      ['ucb_class_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}

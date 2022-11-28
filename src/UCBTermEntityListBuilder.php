<?php

namespace Drupal\ucb_course;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of UCB Term entities.
 *
 * @ingroup ucb_course
 */
class UCBTermEntityListBuilder extends EntityListBuilder {


  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('UCB Term ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\ucb_course\Entity\UCBTermEntity */
    $row['id'] = $entity->id();
    $row['name'] = Link::createFromRoute(
      $entity->label(),
      'entity.ucb_term_entity.edit_form',
      ['ucb_term_entity' => $entity->id()]
    );
    return $row + parent::buildRow($entity);
  }

}

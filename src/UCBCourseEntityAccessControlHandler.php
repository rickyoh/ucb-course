<?php

namespace Drupal\ucb_course;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the UCB Course entity.
 *
 * @see \Drupal\ucb_course\Entity\UCBCourseEntity.
 */
class UCBCourseEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ucb_course\Entity\UCBCourseEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished ucb course entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published ucb course entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit ucb course entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete ucb course entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add ucb course entities');
  }

}

<?php

namespace Drupal\ucb_course;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the UCB Term entity.
 *
 * @see \Drupal\ucb_course\Entity\UCBTermEntity.
 */
class UCBTermEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ucb_course\Entity\UCBTermEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished ucb term entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published ucb term entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit ucb term entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete ucb term entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add ucb term entities');
  }

}

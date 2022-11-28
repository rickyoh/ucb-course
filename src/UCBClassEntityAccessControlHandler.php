<?php

namespace Drupal\ucb_course;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the UCB Class entity.
 *
 * @see \Drupal\ucb_course\Entity\UCBClassEntity.
 */
class UCBClassEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\ucb_course\Entity\UCBClassEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished ucb class entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published ucb class entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit ucb class entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete ucb class entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add ucb class entities');
  }

}

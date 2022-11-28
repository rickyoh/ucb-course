<?php

namespace Drupal\ucb_course\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining UCB Term entities.
 *
 * @ingroup ucb_course
 */
interface UCBTermEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the UCB Term name.
   *
   * @return string
   *   Name of the UCB Term.
   */
  public function getName();

  /**
   * Sets the UCB Term name.
   *
   * @param string $name
   *   The UCB Term name.
   *
   * @return \Drupal\ucb_course\Entity\UCBTermEntityInterface
   *   The called UCB Term entity.
   */
  public function setName($name);

  /**
   * Gets the UCB Term creation timestamp.
   *
   * @return int
   *   Creation timestamp of the UCB Term.
   */
  public function getCreatedTime();

  /**
   * Sets the UCB Term creation timestamp.
   *
   * @param int $timestamp
   *   The UCB Term creation timestamp.
   *
   * @return \Drupal\ucb_course\Entity\UCBTermEntityInterface
   *   The called UCB Term entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the UCB Term published status indicator.
   *
   * Unpublished UCB Term are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the UCB Term is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a UCB Term.
   *
   * @param bool $published
   *   TRUE to set this UCB Term to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\ucb_course\Entity\UCBTermEntityInterface
   *   The called UCB Term entity.
   */
  public function setPublished($published);

}

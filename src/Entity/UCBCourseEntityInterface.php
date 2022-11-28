<?php

namespace Drupal\ucb_course\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining UCB Course entities.
 *
 * @ingroup ucb_course
 */
interface UCBCourseEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the UCB Course name.
   *
   * @return string
   *   Name of the UCB Course.
   */
  public function getName();

  /**
   * Sets the UCB Course name.
   *
   * @param string $name
   *   The UCB Course name.
   *
   * @return \Drupal\ucb_course\Entity\UCBCourseEntityInterface
   *   The called UCB Course entity.
   */
  public function setName($name);

  /**
   * Gets the UCB Course creation timestamp.
   *
   * @return int
   *   Creation timestamp of the UCB Course.
   */
  public function getCreatedTime();

  /**
   * Sets the UCB Course creation timestamp.
   *
   * @param int $timestamp
   *   The UCB Course creation timestamp.
   *
   * @return \Drupal\ucb_course\Entity\UCBCourseEntityInterface
   *   The called UCB Course entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the UCB Course published status indicator.
   *
   * Unpublished UCB Course are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the UCB Course is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a UCB Course.
   *
   * @param bool $published
   *   TRUE to set this UCB Course to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\ucb_course\Entity\UCBCourseEntityInterface
   *   The called UCB Course entity.
   */
  public function setPublished($published);

}

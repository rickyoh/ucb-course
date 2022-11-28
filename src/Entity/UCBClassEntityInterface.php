<?php

namespace Drupal\ucb_course\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining UCB Class entities.
 *
 * @ingroup ucb_course
 */
interface UCBClassEntityInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the UCB Class name.
   *
   * @return string
   *   Name of the UCB Class.
   */
  public function getName();

  /**
   * Sets the UCB Class name.
   *
   * @param string $name
   *   The UCB Class name.
   *
   * @return \Drupal\ucb_course\Entity\UCBClassEntityInterface
   *   The called UCB Class entity.
   */
  public function setName($name);

  /**
   * Gets the UCB Class creation timestamp.
   *
   * @return int
   *   Creation timestamp of the UCB Class.
   */
  public function getCreatedTime();

  /**
   * Sets the UCB Class creation timestamp.
   *
   * @param int $timestamp
   *   The UCB Class creation timestamp.
   *
   * @return \Drupal\ucb_course\Entity\UCBClassEntityInterface
   *   The called UCB Class entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the UCB Class published status indicator.
   *
   * Unpublished UCB Class are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the UCB Class is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a UCB Class.
   *
   * @param bool $published
   *   TRUE to set this UCB Class to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\ucb_course\Entity\UCBClassEntityInterface
   *   The called UCB Class entity.
   */
  public function setPublished($published);

}

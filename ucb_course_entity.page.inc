<?php

/**
 * @file
 * Contains ucb_course_entity.page.inc.
 *
 * Page callback for UCB Course entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for UCB Course templates.
 *
 * Default template: ucb_course_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_ucb_course_entity(array &$variables) {
  // Fetch UCBCourseEntity Entity Object.
  $ucb_course_entity = $variables['elements']['#ucb_course_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

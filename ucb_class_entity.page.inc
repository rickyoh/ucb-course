<?php

/**
 * @file
 * Contains ucb_class_entity.page.inc.
 *
 * Page callback for UCB Class entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for UCB Class templates.
 *
 * Default template: ucb_class_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_ucb_class_entity(array &$variables) {
  // Fetch UCBClassEntity Entity Object.
  $ucb_class_entity = $variables['elements']['#ucb_class_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

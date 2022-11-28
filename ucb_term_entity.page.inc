<?php

/**
 * @file
 * Contains ucb_term_entity.page.inc.
 *
 * Page callback for UCB Term entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for UCB Term templates.
 *
 * Default template: ucb_term_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_ucb_term_entity(array &$variables) {
  // Fetch UCBTermEntity Entity Object.
  $ucb_term_entity = $variables['elements']['#ucb_term_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

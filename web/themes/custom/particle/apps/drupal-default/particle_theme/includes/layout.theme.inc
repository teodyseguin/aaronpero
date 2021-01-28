<?php

/**
 * @file
 * Functions to support theming layouts in the Particle theme.
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function particle_preprocess_page(&$variables) {
  // Themes are not able to declare module dependencies at this time.
  // We use this as a shortcut to guarantee the component library module exists.
  $moduleHandler = \Drupal::service('module_handler');
  if ($moduleHandler->moduleExists('components')) {
    $variables['has_components_module'] = TRUE;
  }

  $node = $variables['node'];

  if (!$node->hasField('field_hide_title')) {
    return;
  }

  $hide_title = $node->get('field_hide_title')->value;

  if ($hide_title == 'true') {
    $variables['hide_title'] = TRUE;
  }
}

<?php

/**
 * @file
 * Functions to support theming layouts in the Particle theme.
 */

/**
 * Implements hook_preprocess_HOOK().
 */
function particle_preprocess_page(&$variables) {
  $helper = \Drupal::service('aaronpero.tools');

  // Themes are not able to declare module dependencies at this time.
  // We use this as a shortcut to guarantee the component library module exists.
  $moduleHandler = \Drupal::service('module_handler');
  if ($moduleHandler->moduleExists('components')) {
    $variables['has_components_module'] = TRUE;
  }

  $node = $variables['node'];

  if (!empty($node)) {
    if ($node->hasField('field_hide_title')) {
      $hide_title = $node->get('field_hide_title')->value;
  
      if ($hide_title == 'true') {
        $variables['hide_title'] = TRUE;
      }
    }
  }

  if ($helper->pathMatcher()->isFrontPage()) {
    $variables['is_front'] = 'front';
  }

  $contact_block = $helper->entityTypeManager()->getStorage('block_content')->load(7);
  $variables['contact_block'] = $helper->entityTypeManager()->getViewBuilder('block_content')->view($contact_block);

  $selling_block = $helper->entityTypeManager()->getStorage('block_content')->load(9);
  $variables['selling_block'] = $helper->entityTypeManager()->getViewBuilder('block_content')->view($selling_block);

  $buying_block = $helper->entityTypeManager()->getStorage('block_content')->load(10);
  $variables['buying_block'] = $helper->entityTypeManager()->getViewBuilder('block_content')->view($buying_block);
}

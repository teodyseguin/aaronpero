<?php

/**
 * Implemens hook_preprocess_paragraph__PARAGRAPH_TYPE().
 */
function particle_preprocess_paragraph__views_reference(array &$variables) {
  $paragraph = $variables['paragraph'];

  if ($paragraph->getType() == 'views_reference') {
    $referenced_views = $paragraph->field_views->referencedEntities();

    if (!empty($referenced_views)) {
      $referenced_views = reset($referenced_views);
      $variables['views_referenced_id'] = $referenced_views->id();
    }
  }
}

/**
 * Implements hook_preprocess_paragraph__PARAGRAPH_TYPE().
 */
function particle_preprocess_paragraph__social_media_links(array &$variables) {
  $paragraph = $variables['paragraph'];
  $variables['field_link'] = $paragraph->get('field_link')->uri;
  $variables['social_media_icon'] = $paragraph->get('field_social_media')->value;
}

/**
 * Implements hook_preprocess_paragraph__PARAGRAPH_TYPE().
 */
function particle_preprocess_paragraph__2_column_image_left_text_right(array &$variables) {
  $paragraph = $variables['paragraph'];
  $terms = $paragraph->field_features->referencedEntities();
  $features = [];

  if (!empty($terms)) {
    foreach ($terms as $term) {
      $features[] = [
        'name' => $term->get('name')->value,
        'icon' => $term->get('field_svg_icon')->value,
      ];
    }

    $variables['features'] = $features;
  }
}

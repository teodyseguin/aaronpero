<?php

/**
 * @file
 * Functions to support theming node entities in the Particle theme.
 */

/**
 * Implements hook_preprocess_node().
 */
function particle_preprocess_node(array &$variables) {
  // Default to turning off byline/submitted.
  $variables['display_submitted'] = FALSE;
}

/**
 * Implements hook_preprocess_node__TYPE().
 */
function particle_preprocess_node__listing(array &$variables) {
  $node = $variables['node'];

  if ($node->hasField('field_streetaddress')) {
    $location = $node->get('field_streetaddress')->value;

    if (!empty($location)) {
      $variables['location'] = $location;
    }
  }

  if ($node->hasField('field_bedrooms')) {
    $bedrooms = $node->get('field_bedrooms')->value;

    if (!empty($bedrooms)) {
      $variables['bedrooms'] = $bedrooms;
    }
  }

  if ($node->hasField('field_bathrooms')) {
    $bathrooms = $node->get('field_bathrooms')->value;

    if (!empty($bathrooms)) {
      $variables['bathrooms'] = $bathrooms;
    }
  }

  if ($node->hasField('field_images')) {
    $images = $node->field_images->referencedEntities();

    if (!empty($images)) {
      $variables['first_image'] = $images[0]->url();
    }
  }

  if ($node->hasField('field_listing_status')) {
    $status = $node->get('field_listing_status')->value;

    if (!empty($status)) {
      $variables['status'] = $status;
    }
  }

  if ($node->hasField('field_display_price')) {
    $display_price = $node->get('field_display_price')->value;

    if (!empty($display_price)) {
      $variables['display_price'] = $display_price;
    }
  }

  if ($node->hasField('field_floorarea')) {
    $floorarea = $node->get('field_floorarea')->value;

    if (!empty($floorarea)) {
      $variables['floorarea'] = $floorarea;
    }
  }

  if ($node->hasField('field_carspacegarage')) {
    $garage = $node->get('field_carspacegarage')->value;

    if (!empty($garage)) {
      $variables['garage'] = $garage;
    }
  }
}

/**
 * Implements hook_preprocess_node__TYPE().
 */
function particle_preprocess_node__testimonial(array &$variables) {
  $node = $variables['node'];
  $body = strip_tags($node->get('body')->value);
  $phrase_array = explode(' ', $body);
  $phrase = implode(' ', array_slice($phrase_array, 0, 20)) . '... More';
  $variables['contents'] = $phrase;

  if ($node->hasField('field_listing')) {
    $listing = $node->field_listing->referencedEntities();

    if (!empty($listing)) {
      $listing = reset($listing);
      $variables['location'] = $listing->get('field_streetaddress')->value;

      $images = $listing->field_images->referencedEntities();

      if (!empty($images)) {
        $variables['first_image'] = $images[0]->url();
      }
      else {
        $variables['first_image'] = 'drupal empty';
      }
    }
    else {
      $variables['first_image'] = 'drupal empty';
    }
  }
}

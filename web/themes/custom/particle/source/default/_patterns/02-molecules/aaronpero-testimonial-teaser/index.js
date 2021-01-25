/**
 * aaronpero-testimonial-teaser
 */

import $ from 'jquery';

// Module dependencies
import 'protons';

// Module template
import './_aaronpero-testimonial-teaser.twig';
import './index.css';
import './feed1.jpg';

export const name = 'aaronpero-testimonial-teaser';

export const defaults = {
  dummyClass: 'js-aaronpero-testimonial-teaser-exists',
};

/**
 * Components may need to run clean-up tasks if they are removed from DOM.
 *
 * @param {jQuery} $context - A piece of DOM
 * @param {Object} settings - Pertinent settings
 */
// eslint-disable-next-line no-unused-vars
export function disable($context, settings) {}

/**
 * Each component has a chance to run when its enable function is called. It is
 * given a piece of DOM ($context) and a settings object. We destructure our
 * component key off the settings object and provide an empty object fallback.
 * Incoming settings override default settings via Object.assign().
 *
 * @param {jQuery} $context - A piece of DOM
 * @param {Object} settings - Settings object
 */
export function enable($context, { aaronperoTestimonialTeaser = {} }) {
  // Find our component within the DOM
  const $aaronperoTestimonialTeaser = $(
    '.aaronpero-testimonial-teaser',
    $context
  );
  // Bail if component does not exist
  if (!$aaronperoTestimonialTeaser.length) {
    return;
  }
  // Merge defaults with incoming settings
  const settings = {
    ...defaults,
    ...aaronperoTestimonialTeaser,
  };
  // An example of what could be done with this component
  $aaronperoTestimonialTeaser.addClass(settings.dummyClass);
}

export default enable;

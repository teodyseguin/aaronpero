/**
 * aaronpero-2-col-image-texts
 */

import $ from 'jquery';

// Module dependencies
import 'protons';

// Module template
import './_aaronpero-2-col-image-texts.twig';
import './index.css';
import './bg-why.png';

export const name = 'aaronpero-2-col-image-texts';

export const defaults = {
  dummyClass: 'js-aaronpero-2-col-image-texts-exists',
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
export function enable($context, { aaronpero2ColImageTexts = {} }) {
  // Find our component within the DOM
  const $aaronpero2ColImageTexts = $('.aaronpero-2-col-image-texts', $context);
  // Bail if component does not exist
  if (!$aaronpero2ColImageTexts.length) {
    return;
  }
  // Merge defaults with incoming settings
  const settings = {
    ...defaults,
    ...aaronpero2ColImageTexts,
  };
  // An example of what could be done with this component
  $aaronpero2ColImageTexts.addClass(settings.dummyClass);
}

export default enable;

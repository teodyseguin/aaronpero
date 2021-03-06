/**
 * aaronpero-header
 */

import $ from 'jquery';

// Module dependencies
import 'protons';

// Module template
import './_aaronpero-header.twig';
import './index.css';
import './aaronpero-logo.png';

export const name = 'aaronpero-header';

export const defaults = {
  dummyClass: 'js-aaronpero-header-exists',
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
export function enable($context, { aaronperoHeader = {} }) {
  // Find our component within the DOM
  const $aaronperoHeader = $('.aaronpero-header', $context);
  // Bail if component does not exist
  if (!$aaronperoHeader.length) {
    return;
  }
  // Merge defaults with incoming settings
  const settings = {
    ...defaults,
    ...aaronperoHeader,
  };
  // An example of what could be done with this component
  $aaronperoHeader.addClass(settings.dummyClass);
}

export default enable;

(function aaronperoHeader() {
  $(document).ready(function documentReady() {
    if ($(window).scrollTop() > 94) {
      $('.aaronpero-header').addClass('animated fadeInDown');
    } else {
      $('.aaronpero-header').removeClass('animated fadeInDown');
    }
  });

  $(window).on('scroll', function windowReady() {
    if ($(window).scrollTop() > 94) {
      $('.aaronpero-header').addClass('animated fadeInDown');
    } else {
      $('.aaronpero-header').removeClass('animated fadeInDown');
    }
  });
})();

// (".top-nav__mobile").removeClass("show"),
// ("html").removeClass("html-hidden"))})
// (window).scrollTop()>94?
// (".top-nav").addClass("animated fadeInDown"):e(".top-nav").removeClass("animated fadeInDown"),
// e(window).on("scroll",function(){e(window).

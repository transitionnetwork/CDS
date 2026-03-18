// Import svg4everybody
import svg4everybody from 'svg4everybody';

// Import Cookies
import Cookies from 'js-cookie';

// Headroom
import Headroom from 'headroom.js/dist/headroom.js';
window.Headroom = Headroom;

export default {
  init() {
    // JavaScript to be fired on all pages

    // Headroom JS
    var navEl = document.querySelector("nav.headroom");
    if (navEl) {
      new Headroom(navEl).init();
    }

    //Iniitalize svg4everybody
    svg4everybody();

    // Listen for notification close
    var closeBtn = document.querySelector('#tofino-notification .close');
    if (closeBtn) {
      closeBtn.addEventListener('click', function() {
        if (tofinoJS.cookieExpires) {
          Cookies.set('tofino-notification-closed', 'yes', {expires: parseInt(tofinoJS.cookieExpires)});
        } else {
          Cookies.set('tofino-notification-closed', 'yes');
        }
      });
    }

    // Show the notification using JS based on the cookie (fixes html caching issue)
    if (tofinoJS.notificationJS === 'true' && !Cookies.get('tofino-notification-closed')) {
      var notificationEl = document.getElementById('tofino-notification');
      if (notificationEl) {
        notificationEl.style.display = '';
      }
    }

    // CSS position: sticky works in all modern browsers — stickyfill removed
  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
  loaded() {
    // Javascript to be fired on page once fully loaded
  }
};

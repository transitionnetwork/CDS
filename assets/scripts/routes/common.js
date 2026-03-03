// Import Cookies
import Cookies from 'js-cookie';

// Import Headroom
import Headroom from 'headroom.js';

export default {
  init() {
    // ── Headroom ────────────────────────────────────────────────────────────
    const navEl = document.querySelector('nav.headroom');
    if (navEl) {
      new Headroom(navEl).init();
    }

    // ── Mobile menu toggle ──────────────────────────────────────────────────
    const toggler = document.querySelector('.navbar-toggler');
    const navCollapse = document.querySelector('.navbar-collapse');

    if (toggler && navCollapse) {
      toggler.addEventListener('click', function () {
        const isExpanded = !toggler.classList.contains('collapsed');
        if (isExpanded) {
          // Close
          toggler.classList.add('collapsed');
          toggler.setAttribute('aria-expanded', 'false');
          navCollapse.classList.remove('show');
        } else {
          // Open
          toggler.classList.remove('collapsed');
          toggler.setAttribute('aria-expanded', 'true');
          navCollapse.classList.add('show');
        }
      });
    }

    // ── Mobile dropdown toggle ──────────────────────────────────────────────
    // Only activate click-based dropdowns on mobile (handled by CSS hover on desktop)
    document.querySelectorAll('.nav-item.dropdown').forEach(function (item) {
      const link = item.querySelector('.nav-link');
      const menu = item.querySelector('.dropdown-menu');
      if (!link || !menu) return;

      link.addEventListener('click', function (e) {
        // Only intercept on mobile
        if (window.innerWidth >= 1024) return;
        e.preventDefault();
        const isOpen = item.classList.contains('open');
        // Close all other open dropdowns
        document.querySelectorAll('.nav-item.dropdown.open').forEach(function (other) {
          other.classList.remove('open');
          const otherMenu = other.querySelector('.dropdown-menu');
          if (otherMenu) otherMenu.classList.remove('show');
          const otherLink = other.querySelector('.nav-link');
          if (otherLink) otherLink.setAttribute('aria-expanded', 'false');
        });
        if (!isOpen) {
          item.classList.add('open');
          menu.classList.add('show');
          link.setAttribute('aria-expanded', 'true');
        }
      });
    });

    // Close dropdowns when clicking outside
    document.addEventListener('click', function (e) {
      if (!e.target.closest('.nav-item.dropdown')) {
        document.querySelectorAll('.nav-item.dropdown.open').forEach(function (item) {
          item.classList.remove('open');
          const menu = item.querySelector('.dropdown-menu');
          if (menu) menu.classList.remove('show');
          const link = item.querySelector('.nav-link');
          if (link) link.setAttribute('aria-expanded', 'false');
        });
      }
    });

    // ── Notifications ────────────────────────────────────────────────────────
    const notificationClose = document.querySelector('#tofino-notification .close');
    if (notificationClose) {
      notificationClose.addEventListener('click', function () {
        if (typeof tofinoJS !== 'undefined' && tofinoJS.cookieExpires) {
          Cookies.set('tofino-notification-closed', 'yes', { expires: parseInt(tofinoJS.cookieExpires) });
        } else {
          Cookies.set('tofino-notification-closed', 'yes');
        }
      });
    }

    // Show notification via JS if configured (fixes HTML caching)
    const notification = document.getElementById('tofino-notification');
    if (
      notification &&
      typeof tofinoJS !== 'undefined' &&
      tofinoJS.notificationJS === 'true' &&
      !Cookies.get('tofino-notification-closed')
    ) {
      notification.style.display = '';
    }
  },

  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },

  loaded() {
    // Javascript to be fired on page once fully loaded
  },
};

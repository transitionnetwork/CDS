// Import wp-admin CSS — Vite processes this and outputs to dist/css/wp-admin.css
import '../styles/css/wp-admin.css';

import Cookies from 'js-cookie';

(function($) {
  $(document).ready(function() {
    $('.maintenance-mode-alert button').on('click', function() {
      Cookies.set('tofino_maintenance_alert_dismissed', 'true');
      $('.maintenance-mode-alert').fadeOut('fast');
    });
  });
}(jQuery));

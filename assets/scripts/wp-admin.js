import Cookies from 'js-cookie';

document.addEventListener('DOMContentLoaded', function() {
  var alertBtn = document.querySelector('.maintenance-mode-alert button');
  if (alertBtn) {
    alertBtn.addEventListener('click', function() {
      Cookies.set('tofino_maintenance_alert_dismissed', 'true');
      var alertEl = document.querySelector('.maintenance-mode-alert');
      if (alertEl) {
        alertEl.style.transition = 'opacity 0.2s';
        alertEl.style.opacity = '0';
        setTimeout(function() { alertEl.style.display = 'none'; }, 200);
      }
    });
  }
});

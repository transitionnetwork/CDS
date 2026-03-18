export default {
  loaded() {
    var dashboard = document.getElementById('account-dashboard');
    if (!dashboard) return;

    var navLinks = dashboard.querySelectorAll('.dashboard-nav-link');
    var panels = dashboard.querySelectorAll('.dashboard-panel');

    // Click handler for sidebar nav
    navLinks.forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        var panelId = this.dataset.panel;
        activatePanel(panelId);
        history.replaceState(null, null, this.getAttribute('href'));
      });
    });

    // Activate tab by ?tab= param
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') == 'file') {
      activatePanel('panel-initiative-admin');
    }

    // Activate tab by URL hash
    if (location.hash) {
      var hashId = location.hash.replace('#', '').replace(/\/+$/, '');
      // Map old hash format (nav-*) to new panel ID (panel-*)
      var panelId = hashId.replace(/^nav-/, 'panel-');
      activatePanel(panelId);
    }

    function activatePanel(panelId) {
      var targetPanel = document.getElementById(panelId);
      if (!targetPanel) return;

      // Deactivate all
      navLinks.forEach(function(l) { l.classList.remove('active'); });
      panels.forEach(function(p) { p.classList.remove('active'); });

      // Activate target
      targetPanel.classList.add('active');
      var targetLink = dashboard.querySelector('.dashboard-nav-link[data-panel="' + panelId + '"]');
      if (targetLink) targetLink.classList.add('active');
    }

    // Download count increment
    document.querySelectorAll('.download-file').forEach(function(el) {
      el.addEventListener('click', function() {
        var fileTile = this.closest('.file-tile');
        if (fileTile) {
          incrementDownload(fileTile);
        }
      });
    });

    function incrementDownload(fileTile) {
      var countEl = fileTile.querySelector('.download-count');
      var downloadCount = parseInt(countEl.dataset.value, 10);

      var formData = new URLSearchParams();
      formData.append('action', 'incrementDownload');
      formData.append('value[file_id]', fileTile.dataset.id);
      formData.append('value[download_count]', downloadCount);

      fetch(tofinoJS.ajaxUrl, {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: formData.toString()
      })
      .then(function(res) { return res.json(); })
      .then(function(response) {
        console.log(response);
        countEl.textContent = downloadCount + 1;
        countEl.dataset.value = downloadCount + 1;
      })
      .catch(function(err) {
        console.log('Error:', err);
      });
    }
  },
  finalize() {}
};

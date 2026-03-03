// account.js — vanilla JS tab switching (replaces Bootstrap jQuery .tab() plugin)
// jQuery still available via window.jQuery for AJAX calls below.
var $ = window.jQuery;

/**
 * Activate a Bootstrap-style tab by its href target (e.g. "#nav-account-details")
 * Works with .nav-tabs / .tab-content / .tab-pane markup.
 */
function showTab(targetId) {
  // targetId may be passed with or without the leading '#'
  const id = targetId.startsWith('#') ? targetId.slice(1) : targetId;

  // Deactivate all tab links
  document.querySelectorAll('[data-toggle="tab"]').forEach(function(link) {
    link.classList.remove('active');
    link.setAttribute('aria-selected', 'false');
  });

  // Deactivate all tab panels
  document.querySelectorAll('.tab-pane').forEach(function(pane) {
    pane.classList.remove('show', 'active');
  });

  // Activate the matching tab link
  const activeLink = document.querySelector('[data-toggle="tab"][href="#' + id + '"]');
  if (activeLink) {
    activeLink.classList.add('active');
    activeLink.setAttribute('aria-selected', 'true');
  }

  // Activate the matching tab panel
  const activePane = document.getElementById(id);
  if (activePane) {
    activePane.classList.add('show', 'active');
  }
}

export default {
  loaded() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('tab') === 'file') {
      showTab('nav-filesharing');
    }

    function incrementDownload($el) {
      const downloadCount = $el.find('.download-count').data('value');
      $.ajax({
        url: tofinoJS.ajaxUrl,
        type: 'POST',
        cache: false,
        data: {
          action: 'incrementDownload',
          value: {
            file_id: $el.data('id'),
            download_count: downloadCount
          }
        },
        dataType: 'json',
        success: function(response) {
          console.log(response);
          $el.find('.download-count').text(downloadCount + 1);
          $el.find('.download-count').data('value', downloadCount + 1);
          $el.find('.download-count').attr('data-value', downloadCount + 1);
        },
        error: function(jqxhr, status, exception) {
          console.log('JQXHR:', jqxhr);
          console.log('Status:', status);
          console.log('Exception:', exception);
        }
      });
    }

    document.querySelectorAll('.download-file').forEach(function(el) {
      el.addEventListener('click', function() {
        incrementDownload($(this).closest('.file-tile'));
      });
    });
  },

  finalize() {
    const baseUrl = location.href.replace(/\/$/, '').split('#')[0];

    // On page load, open tab matching URL hash
    if (location.hash) {
      showTab(location.hash);
      history.replaceState(null, null, baseUrl + location.hash + '/');
      setTimeout(function() { window.scrollTo(0, 0); }, 400);
    }

    // On tab click: switch panel + update URL hash
    document.querySelectorAll('[data-toggle="tab"]').forEach(function(link) {
      link.addEventListener('click', function(e) {
        e.preventDefault();
        const hash = this.getAttribute('href'); // e.g. "#nav-account-details"
        showTab(hash);
        const newUrl = hash === '#home'
          ? baseUrl + '/'
          : baseUrl + hash + '/';
        history.replaceState(null, null, newUrl);
      });
    });
  }
};

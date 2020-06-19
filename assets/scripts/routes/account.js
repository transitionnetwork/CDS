// We need jQuery
var $ = window.jQuery;
import displayBarGraph from '../shared/graph-all';

export default {
  loaded() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    if(urlParams.get('tab') == 'file') {
      $('#nav-filesharing-tab').tab('show')
    }

    displayBarGraph();

    function incrementDownload($el) {
      const downloadCount = $el.find('.download-count').data('value')
      
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
        success: function (response) {
          console.log(response)
          $el.find('.download-count').text(downloadCount + 1)
          $el.find('.download-count').data('value', downloadCount + 1)
          //update in the DOM
          $el.find('.download-count').attr('data-value', downloadCount + 1)
        },
        error: function (jqxhr, status, exception) {
          console.log('JQXHR:', jqxhr);
          console.log('Status:', status);
          console.log('Exception:', exception);
        }
      })
    }

    $('.download-file').on('click', function() {
      incrementDownload($(this).closest('.file-tile'))
    })
  }
};

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

  },
  finalize() {
    let url = location.href.replace(/\/$/, "");
    
    //open tab from loaded url
    if (location.hash) {
      const hash = url.split("#");
      $('#nav-tab a[href="#'+hash[1]+'"]').tab("show");
      url = location.href.replace(/\/#/, "#");
      history.replaceState(null, null, url);
      setTimeout(() => {
        $(window).scrollTop(0);
      }, 400);
    } 

    //show hash links on tabs
    $('a[data-toggle="tab"]').on("click", function() {
      let newUrl;
      const hash = $(this).attr("href");
      if(hash == "#home") {
        newUrl = url.split("#")[0];
      } else {
        newUrl = url.split("#")[0] + hash;
      }
      newUrl += "/";
      history.replaceState(null, null, newUrl);
    });
  }
};

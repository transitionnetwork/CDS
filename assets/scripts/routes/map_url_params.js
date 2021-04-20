var $ = window.jQuery;
import displayMap from '../shared/display-map';
// import select2 from '../shared/select2';

export default {
  loaded() {
    const queryString = window.location.search;

    if (queryString) {
      const urlParams = new URLSearchParams(queryString);
      const map_type = urlParams.get('map_type');
      const hub_name = urlParams.get('hub_name');
      const country = urlParams.get('country');
      const training = urlParams.get('training');
      const search = urlParams.get('s');

      $.ajax({
        url: tofinoJS.ajaxUrl,
        type: 'POST',
        cache: false,
        data: {
          action: 'getMarkers',
          value: {
            map_type: map_type,
            hub_name: hub_name,
            country: country,
            training: training,
            search: search
          }
        },
        dataType: 'json',
        success: function (response) {
          console.log('success')
          console.log(response);
          // $('.map-loading').hide();
          // $('#map-panel').show();
          // displayMap(response);
        },
        error: function (jqxhr, status, exception) {
          console.log('JQXHR:', jqxhr);
          console.log('Status:', status);
          console.log('Exception:', exception);
        }
      })
    }

  },
  finalize() {
    // select2();
  }
};

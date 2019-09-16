var $ = window.jQuery;
import L from 'leaflet';
import 'leaflet.markercluster';

export default {
  loaded() {
    var map = L.map('iframe_map').setView([16.5, 11], 2);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
      maxZoom: 18,
    }).addTo(map);

    map.scrollWheelZoom.disable();
    map.on('focus', function () { map.scrollWheelZoom.enable(); });
    map.on('blur', function () { map.scrollWheelZoom.disable(); });

    function displayMap(response, map) {
      var markerIcon = L.icon({
        iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon.png',
        iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-2x.png',
        shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        tooltipAnchor: [16, -28],
        shadowSize: [41, 41]
      });

      var marker;
      var range = [];
      var markers = L.markerClusterGroup();

      for (var i = 0; i < response.length; i++) {
        if (response[i].center_lat && response[i].center_lng) {
          marker = L.marker([response[i].center_lat, response[i].center_lng], { icon: markerIcon });
          marker.bindPopup('<h5>' + response[i].title + '</h5><div><a href="' + response[i].permalink + '" target="_top" class="btn btn-sm btn-primary">View</a></div>');
          markers.addLayer(marker);
          
          range.push([response[i].center_lat, response[i].center_lng]);
        }
      }

      var bounds = L.latLngBounds(range);
      map.fitBounds(bounds);
      map.addLayer(markers);
    }

    $.ajax({
      url: tofinoJS.ajaxUrl,
      type: 'POST',
      data: {
        action: 'getMapMarkers',
        value: {
          hub_name: $('#iframe_map').data('hub'),
          country: $('#iframe_map').data('country'),
          search: $('#iframe_map').data('search')
        }
      },
      dataType: 'json',
      success: function (response) {
        $('#map-loading').hide();
        displayMap(response, map);
      },
      error: function (jqxhr, status, exception) {
        console.log('JQXHR:', jqxhr);
        console.log('Status:', status);
        console.log('Exception:', exception);
      }
    })
  }
};

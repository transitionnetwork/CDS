var $ = window.jQuery;
import L from 'leaflet';
import 'leaflet.markercluster';
import 'leaflet-search';
import 'leaflet.locatecontrol';

export default {
  loaded() {
    function updateMap(map, checked) {
      $.ajax({
        url: tofinoJS.ajaxUrl,
        type: 'POST',
        cache: false,
        data: {
          action: 'getHubMarkers',
          value: {
            training: checked,
            hub_page: true
          }
        },
        dataType: 'json',
        success: function (response) {
          // console.log(response);
          
          $('.map-loading').hide();
          $('#map-panel').show();
          displayMarkers(response, map);
        },
        error: function (jqxhr, status, exception) {
          console.log('JQXHR:', jqxhr);
          console.log('Status:', status);
          console.log('Exception:', exception);
        }
      })
    }
    
    function displayMarkers(response, map) {
      let hResponse = response['h_markers'];

      if (hResponse) {
        let hubMarkerIcon = L.icon({
          iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-hub.png',
          iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-hub-2x.png',
          shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          tooltipAnchor: [16, -28],
          shadowSize: [41, 41]
        });

        let marker;
        let range = [];
        let clusterMarkers = L.markerClusterGroup();
        
        clusterMarkers = L.markerClusterGroup({
          iconCreateFunction: function (cluster) {
            return L.divIcon({
              html: '<div><span>' + cluster.getChildCount() + '</span></div>',
              className: 'hub-cluster marker-cluster',
              iconSize: [40, 40]
            });
          }
        });
        
        for (let i = 0; i < hResponse.length; i++) {
          if (hResponse[i].lat && hResponse[i].lng) {
            marker = L.marker([hResponse[i].lat, hResponse[i].lng], { icon: hubMarkerIcon });
            marker.bindPopup('<h5>' + hResponse[i].title + '</h5><div><a href="' + hResponse[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
            clusterMarkers.addLayer(marker);
            range.push([hResponse[i].lat, hResponse[i].lng]);
          }
        }

        let bounds = L.latLngBounds(range);
        map.fitBounds(bounds);

        map.addLayer(clusterMarkers);
      }
    }

    function initMap() {
      var map = L.map('iframe_map', { "tap": false }).setView([0, 0], 3);
  
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
        maxZoom: 18,
      }).addTo(map);
  
      map.scrollWheelZoom.disable();
      map.on('focus', function () { map.scrollWheelZoom.enable(); });
      map.on('blur', function () { map.scrollWheelZoom.disable(); });
      
      return map;
    }

    ///
    let checked
    $('input#training').on('click', function() {
      //hide existing markers
      map.remove();
      map = initMap();
      
      $('.map-loading').show();
      $('#map-panel').hide();      
      
      checked = $(this).is(':checked')
      updateMap(map, checked)
    });

    let map = initMap()
    updateMap(map, checked)
  }
};

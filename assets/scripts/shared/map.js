var $ = window.jQuery;
import L from 'leaflet';
import 'leaflet.markercluster';
import 'leaflet-search';
import 'leaflet.locatecontrol';

export default function() {
  var map = L.map('iframe_map', { "tap": false }).setView([0, 0], 3);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18,
  }).addTo(map);

  map.addControl(new L.Control.Search({
    url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
    jsonpParam: 'json_callback',
    propertyName: 'display_name',
    propertyLoc: ['lat', 'lon'],
    marker: L.circleMarker([0, 0], { radius: 30 }),
    autoCollapse: true,
    autoType: false,
    minLength: 2,
    zoom: 13
  }));

  L.control.locate({
    locateOptions: {
      maxZoom: 13
    }
  }).addTo(map);

  map.scrollWheelZoom.disable();
  map.on('focus', function () { map.scrollWheelZoom.enable(); });
  map.on('blur', function () { map.scrollWheelZoom.disable(); });

  $('#map-search').on('keyup', function () {
    console.log($(this).val());
  })

  function displayMap(response, map) {
    var iResponse = response['i_markers'];
    var hResponse = response['h_markers'];

    var initiativeMarkerIcon = L.icon({
      iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon.png',
      iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-2x.png',
      shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      tooltipAnchor: [16, -28],
      shadowSize: [41, 41]
    });

    var hubMarkerIcon = L.icon({
      iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-hub.png',
      iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-hub-2x.png',
      shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      tooltipAnchor: [16, -28],
      shadowSize: [41, 41]
    });

    var marker;
    var range = [];
    var clusterMarkers = L.markerClusterGroup();
    var i;

    for (i = 0; i < iResponse.length; i++) {
      if (iResponse[i].lat && iResponse[i].lng) {
        marker = L.marker([iResponse[i].lat, iResponse[i].lng], { icon: initiativeMarkerIcon });
        marker.bindPopup('<h5>' + iResponse[i].title + '</h5><div><a href="' + iResponse[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
        clusterMarkers.addLayer(marker);
        range.push([iResponse[i].lat, iResponse[i].lng]);
      }
    }

    map.addLayer(clusterMarkers);

    var bounds = L.latLngBounds(range);
    map.fitBounds(bounds);

    clusterMarkers = L.markerClusterGroup({
      iconCreateFunction: function (cluster) {
        return L.divIcon({
          html: '<div><span>' + cluster.getChildCount() + '</span></div>',
          className: 'hub-cluster marker-cluster',
          iconSize: [40, 40]
        });
      }
    });

    if (hResponse) {
      for (i = 0; i < hResponse.length; i++) {
        if (hResponse[i].lat && hResponse[i].lng) {
          marker = L.marker([hResponse[i].lat, hResponse[i].lng], { icon: hubMarkerIcon });
          marker.bindPopup('<h5>' + hResponse[i].title + '</h5><div><a href="' + hResponse[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
          clusterMarkers.addLayer(marker);
        }
      }
    }

    map.addLayer(clusterMarkers);


    if (iResponse) {
      $('.key .initiative').append('<span>(' + iResponse.length + ')</span>');
    }
    if (hResponse) {
      $('.key .hub').append('<span>(' + hResponse.length + ')</span>');
    }
  }

  function loadMap() {
    let hub_ids = $('#iframe_map').data('hub')
    
    if(hub_ids.length) {
      hub_ids = hub_ids.split(',')
    }

    $.ajax({
      url: tofinoJS.ajaxUrl,
      type: 'POST',
      cache: false,
      data: {
        action: 'getMapMarkers',
        value: {
          hub_name: hub_ids,
          country: $('#iframe_map').data('country'),
          search: $('#iframe_map').data('search')
        }
      },
      dataType: 'json',
      success: function (response) {
        // console.log(response);
        
        $('.map-loading').hide();
        $('#map-panel').show();
        displayMap(response, map);
      },
      error: function (jqxhr, status, exception) {
        console.log('JQXHR:', jqxhr);
        console.log('Status:', status);
        console.log('Exception:', exception);
      }
    })
  
    $('#iframe_map button.close').on('click', function () {
      $(this).closest('#map-panel').hide();
    })
  
    $('#iframe_map button.my-location').on('click', function () {
      map.locate({ setView: true, maxZoom: 12 });
    })
  }

  loadMap();
}

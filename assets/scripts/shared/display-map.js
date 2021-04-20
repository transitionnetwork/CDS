var $ = window.jQuery;
import L from 'leaflet';
import 'leaflet.markercluster';
import 'leaflet-search';
import 'leaflet.locatecontrol';

export default function (response) {
  const h_markers = response['h_markers'];
  const i_markers = response['i_markers'];
  
  const map = L.map('iframe_map', { "tap": false }).setView([0, 0], 3);

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

  $('#iframe_map button.close').on('click', function () {
    $(this).closest('#map-panel').hide();
  })

  $('#iframe_map button.my-location').on('click', function () {
    map.locate({ setView: true, maxZoom: 12 });
  })

  /// maybe split the function here

  const initiativeMarkerIcon = L.icon({
    iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon.png',
    iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-2x.png',
    shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
    iconSize: [25, 41],
    iconAnchor: [12, 41],
    popupAnchor: [1, -34],
    tooltipAnchor: [16, -28],
    shadowSize: [41, 41]
  });

  const hubMarkerIcon = L.icon({
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

  for (i = 0; i < i_markers.length; i++) {
    if (i_markers[i].lat && i_markers[i].lng) {
      marker = L.marker([i_markers[i].lat, i_markers[i].lng], { icon: initiativeMarkerIcon });
      marker.bindPopup('<h5>' + i_markers[i].title + '</h5><div><a href="' + i_markers[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
      clusterMarkers.addLayer(marker);
      range.push([i_markers[i].lat, i_markers[i].lng]);
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

  if (h_markers) {
    for (i = 0; i < h_markers.length; i++) {
      if (h_markers[i].lat && h_markers[i].lng) {
        marker = L.marker([h_markers[i].lat, h_markers[i].lng], { icon: hubMarkerIcon });
        marker.bindPopup('<h5>' + h_markers[i].title + '</h5><div><a href="' + h_markers[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
        clusterMarkers.addLayer(marker);
      }
    }
  }

  map.addLayer(clusterMarkers);
}

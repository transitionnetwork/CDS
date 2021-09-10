var $ = window.jQuery;
import L from 'leaflet';

export default function () {
  var lat = $('#single-map').data('lat');
  var lng = $('#single-map').data('lng');
  var zoom = $('#single-map').data('zoom');

  var map = L.map('single-map').setView([lat, lng], zoom);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18,
  }).addTo(map);

  var markerIcon;

  if ($('body').hasClass('tax-hub')) {
    markerIcon = L.icon({
      iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-hub.png',
      iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-hub-2x.png',
      shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      tooltipAnchor: [16, -28],
      shadowSize: [41, 41]
    });
  } else {
    markerIcon = L.icon({
      iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon.png',
      iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-2x.png',
      shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      tooltipAnchor: [16, -28],
      shadowSize: [41, 41]
    });
  }

  var marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);

  if ($('#marker-address').length) {
    marker.bindPopup($('#marker-address').data('address'));
  }
}

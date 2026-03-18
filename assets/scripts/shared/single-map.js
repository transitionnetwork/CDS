import L from 'leaflet';

export default function () {
  var mapEl = document.getElementById('single-map');
  var lat = parseFloat(mapEl.dataset.lat);
  var lng = parseFloat(mapEl.dataset.lng);
  var zoom = parseInt(mapEl.dataset.zoom, 10);

  var map = L.map('single-map').setView([lat, lng], zoom);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
    maxZoom: 18,
  }).addTo(map);

  var markerIcon;

  if (document.body.classList.contains('tax-hub')) {
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
  } else if (document.body.classList.contains('single-trainers')) {
    markerIcon = L.icon({
      iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-trainer.png',
      iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-trainer-2x.png',
      shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      tooltipAnchor: [16, -28],
      shadowSize: [41, 41]
    });
  } else {
    markerIcon = L.icon({
      iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-initiative.png',
      iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-initiative-2x.png',
      shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      tooltipAnchor: [16, -28],
      shadowSize: [41, 41]
    });
  }

  var marker = L.marker([lat, lng], { icon: markerIcon }).addTo(map);

  var addressEl = document.getElementById('marker-address');
  if (addressEl) {
    marker.bindPopup(addressEl.dataset.address);
  }
}

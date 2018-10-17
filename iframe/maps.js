var map = L.map('iframe_map').setView([51.505, -0.09], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { id: 'iframe_map' }).addTo(map);

L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
  attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
  maxZoom: 18,
  id: 'mapbox.streets',
  accessToken: 'pk.eyJ1IjoieGluYyIsImEiOiJjam5kY3p2MnUwYnN0M3FueWx0dDNuenNuIn0.eWT18AhwYTUInDFyqSCciw'
}).addTo(map);

var marker, lat, lng, title, link;
var markers = [];

var points = document.getElementsByClassName("point");
for (i = 0; i < points.length; i++) {
  lat = points[i].getAttribute('data-lat');
  lng = points[i].getAttribute('data-lng');
  title = points[i].getAttribute('data-title');
  link = points[i].getAttribute('data-link');
  marker = L.marker([lat, lng]).addTo(map);
  marker.bindPopup(title + '<br/><a href=' + link + '>Link</a>');
  markers.push([lat, lng]);
}

var bounds = L.latLngBounds(markers);
map.fitBounds(bounds);

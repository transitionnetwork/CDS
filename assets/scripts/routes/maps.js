import L from 'leaflet';


export default {
  init() {
    // JavaScript to be fired on contact page page
  },
  loaded() {
    console.log('archive');
    // Javascript to be fired on page once fully loaded
    var map = L.map('iframe_map').setView([51.505, -0.09], 13);

    L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
      attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
      maxZoom: 18,
      id: 'mapbox.streets',
      accessToken: 'pk.eyJ1IjoieGluYyIsImEiOiJjam5lbDVnYm4wdjk2M3FzZm4xZDloMWw0In0.YhMrHlc_Yc_otwG_5hrVGw'
    }).addTo(map);

    map.scrollWheelZoom.disable();
    map.on('focus', function () { map.scrollWheelZoom.enable(); });
    map.on('blur', function () { map.scrollWheelZoom.disable(); });

    var marker, lat, lng, title, link, i;
    var markers = [];

    var points = document.getElementsByClassName("point");
    for (i = 0; i < points.length; i++) {
      lat = points[i].getAttribute('data-lat');
      lng = points[i].getAttribute('data-lng');
      title = points[i].getAttribute('data-title');
      link = points[i].getAttribute('data-link');
      marker = L.marker([lat, lng]).addTo(map);
      marker.bindPopup(title + '<br/><a href=' + link + '>&raquo;View initiative</a>');
      markers.push([lat, lng]);
    }

    var bounds = L.latLngBounds(markers);
    map.fitBounds(bounds);
  }
};

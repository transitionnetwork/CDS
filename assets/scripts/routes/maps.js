import L from 'leaflet';


export default {
  init() {
    // JavaScript to be fired on contact page page
  },
  loaded() {
    // Javascript to be fired on page once fully loaded
    var map = L.map('iframe_map').setView([51.505, -0.09], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
      maxZoom: 18,
    }).addTo(map);

    map.scrollWheelZoom.disable();
    map.on('focus', function () { map.scrollWheelZoom.enable(); });
    map.on('blur', function () { map.scrollWheelZoom.disable(); });

    var marker, lat, lng, title, link, i;
    var markers = [];
    var templateUrl = document.getElementById("template-url").getAttribute("url");
    console.log(templateUrl); 

    var markerIcon = L.icon({
      iconUrl: templateUrl + '/dist/img/icons/marker-icon.png',
      iconRetinaUrl: templateUrl + '/dist/img/icons/marker-icon-2x.png',
      shadowUrl: templateUrl + '/dist/img/icons/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      tooltipAnchor: [16, -28],
      shadowSize: [41, 41]
    });

    var points = document.getElementsByClassName("point");
    for (i = 0; i < points.length; i++) {
      lat = points[i].getAttribute('data-lat');
      lng = points[i].getAttribute('data-lng');
      title = points[i].getAttribute('data-title');
      link = points[i].getAttribute('data-link');
      //excerpt = points[i].getAttribute('data-excerpt');
      marker = L.marker([lat, lng], {icon: markerIcon}).addTo(map);
      marker.bindPopup('<h5>' + title + '</h5><div><a href="' + link + '" target="_top">&raquo;View initiative</a></div>');
      markers.push([lat, lng]);
    }

    var bounds = L.latLngBounds(markers);
    map.fitBounds(bounds);
  }
};

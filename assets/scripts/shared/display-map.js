import L from 'leaflet';
import * as GeoSearch from 'leaflet-geosearch';
import { locate as locateControl } from 'leaflet.locatecontrol';

// Expose L globally — markercluster UMD wrapper needs window.L
window.L = L;

var _pluginsLoaded = false;
async function ensurePlugins() {
  if (_pluginsLoaded) return;
  await import('leaflet.markercluster');
  _pluginsLoaded = true;
}

export default async function () {
  await ensurePlugins();
  function getUpdateParams() {
    var url = new URL(window.location.href)
    var params = new URLSearchParams(url.search)

    return params;
  }

  function getMarkerParams() {
    var params = {}
    for (let p of getUpdateParams()) {
      params[p[0]] = p[1];
    }

    return params
  }

  function initialiseMap() {
    map = L.map('map-iframe', { "tap": false }).setView([0, 0], 3);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      attribution: 'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors',
      maxZoom: 18,
    }).addTo(map);

    locateControl({
      locateOptions: {
        maxZoom: 13
      }
    }).addTo(map);

    map.scrollWheelZoom.disable();
    map.on('focus', function () { map.scrollWheelZoom.enable(); });
    map.on('blur', function () { map.scrollWheelZoom.disable(); });

    //new search
    const search = new GeoSearch.GeoSearchControl({
      provider: new GeoSearch.OpenStreetMapProvider(),
      position: 'topleft',
      marker: {
        icon: L.icon({
          iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-red.png',
          iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-red-2x.png',
          shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          tooltipAnchor: [16, -28],
          shadowSize: [41, 41]
        })
      }
    })

    map.addControl(search);

    return map
  }

  function displayMarkers(response) {
    var initiativeMarkerIcon = L.icon({
      iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-initiative.png',
      iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-initiative-2x.png',
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

    var trainerMarkerIcon = L.icon({
      iconUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-trainer.png',
      iconRetinaUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-icon-trainer-2x.png',
      shadowUrl: tofinoJS.themeUrl + '/dist/img/icons/marker-shadow.png',
      iconSize: [25, 41],
      iconAnchor: [12, 41],
      popupAnchor: [1, -34],
      tooltipAnchor: [16, -28],
      shadowSize: [41, 41]
    });

    var marker;
    var range = [];

    for (const i in response.initiatives) {
      if (response.initiatives[i].lat && response.initiatives[i].lng) {

        if(response.initiatives[i].type === 'initiatives') {
          marker = L.marker([response.initiatives[i].lat, response.initiatives[i].lng], { icon: initiativeMarkerIcon, opacity: response.initiatives[i].opacity });

          marker.bindPopup('<h5>' + response.initiatives[i].title + '</h5><div class="mt-2">Last logged in: ' + response.initiatives[i].age + ' days ago</div><div class="mt-2"><a href="' + response.initiatives[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
          clusterMarkers.addLayer(marker);

          range.push([response.initiatives[i].lat, response.initiatives[i].lng]);
        }
      }
    }

    map.addLayer(clusterMarkers);

    /////

    hubMarkers = L.markerClusterGroup({
      iconCreateFunction: function (cluster) {
        return L.divIcon({
          html: '<div><span>' + cluster.getChildCount() + '</span></div>',
          className: 'hub-cluster marker-cluster',
          iconSize: [40, 40]
        });
      }
    });

    for (const i in response.hubs) {
      if (response.hubs[i].lat && response.hubs[i].lng) {

        if (response.hubs[i].type === 'hubs') {
          marker = L.marker([response.hubs[i].lat, response.hubs[i].lng], { icon: hubMarkerIcon });

          marker.bindPopup('<h5>' + response.hubs[i].title + '</h5><div><a href="' + response.hubs[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
          hubMarkers.addLayer(marker);

          range.push([response.hubs[i].lat, response.hubs[i].lng]);
        }
      }
    }

    map.addLayer(hubMarkers);

    /////

    trainerMarkers = L.markerClusterGroup({
      iconCreateFunction: function (cluster) {
        return L.divIcon({
          html: '<div><span>' + cluster.getChildCount() + '</span></div>',
          className: 'trainer-cluster marker-cluster',
          iconSize: [40, 40]
        });
      }
    });

    for (const i in response.trainers) {
      if (response.trainers[i].lat && response.trainers[i].lng) {

        if (response.trainers[i].type === 'trainers') {
          marker = L.marker([response.trainers[i].lat, response.trainers[i].lng], { icon: trainerMarkerIcon });

          marker.bindPopup('<h5>' + response.trainers[i].title + '</h5><div><a href="' + response.trainers[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
          trainerMarkers.addLayer(marker);

          range.push([response.trainers[i].lat, response.trainers[i].lng]);
        }
      }
    }

    map.addLayer(trainerMarkers);

    /////

    var bounds = L.latLngBounds(range).pad(0);
    map.fitBounds(bounds);

    //add counts
    var countInitiatives = Object.keys(response.initiatives).length;
    var countTrainers = Object.keys(response.trainers).length;
    var countHubs = Object.keys(response.hubs).length;

    var initiativeCountEl = document.querySelector('.key .initiative .count');
    if (initiativeCountEl) initiativeCountEl.innerHTML = '(' + countInitiatives + ')';

    var trainerCountEl = document.querySelector('.key .trainer .count');
    if (trainerCountEl) trainerCountEl.innerHTML = '(' + countTrainers + ')';

    document.querySelectorAll('.key .hub .count, #filter-type span.hubs').forEach(function(el) {
      el.innerHTML = '(' + countHubs + ')';
    });
  }

  var CACHE_TTL = 5 * 60 * 1000; // 5 minutes

  function cacheKey(params) {
    return 'mapMarkers:' + JSON.stringify(params, Object.keys(params).sort());
  }

  function getCached(params) {
    try {
      var raw = sessionStorage.getItem(cacheKey(params));
      if (!raw) return null;
      var entry = JSON.parse(raw);
      if (Date.now() - entry.ts > CACHE_TTL) {
        sessionStorage.removeItem(cacheKey(params));
        return null;
      }
      return entry.data;
    } catch (e) {
      return null;
    }
  }

  function setCache(params, data) {
    try {
      sessionStorage.setItem(cacheKey(params), JSON.stringify({ ts: Date.now(), data: data }));
    } catch (e) {
      // sessionStorage full — silently ignore
    }
  }

  function renderResponse(response) {
    var noResultsEl = document.getElementById('map-no-results');
    if (noResultsEl) noResultsEl.style.display = 'none';

    clusterMarkers = L.markerClusterGroup({ chunkedLoading: true });
    hubMarkers = L.markerClusterGroup({ chunkedLoading: true });
    trainerMarkers = L.markerClusterGroup({ chunkedLoading: true });

    if (Object.keys(response.initiatives).length > 0 || Object.keys(response.hubs).length > 0 || Object.keys(response.trainers).length > 0) {
      displayMarkers(response);
    } else {
      if (noResultsEl) noResultsEl.style.display = '';
    }
  }

  function getMarkers(params) {
    var cached = getCached(params);

    if (cached) {
      renderResponse(cached);

      // revalidate in background
      fetchMarkers(params, function (response) {
        setCache(params, response);
      });
      return;
    }

    fetchMarkers(params, function (response) {
      setCache(params, response);
      renderResponse(response);
    });
  }

  function fetchMarkers(params, callback) {
    var formData = new URLSearchParams();
    formData.append('action', 'getMarkers');
    // Serialize params object as value[key]=val
    for (var key in params) {
      if (params.hasOwnProperty(key)) {
        formData.append('value[params][' + key + ']', params[key]);
      }
    }

    fetch(tofinoJS.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData.toString()
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      callback(response);
    })
    .catch(function(err) {
      console.log('Error:', err);
    });
  }

  function udpateFilterUi(params) {
    var filterTypeSelect = document.querySelector('#filter-type select');
    if (filterTypeSelect) {
      filterTypeSelect.value = params.get('type') || '1';
    }

    var filterHubSelect = document.querySelector('#filter-hub select');
    if (filterHubSelect) {
      filterHubSelect.value = params.get('hub_name') || '';
    }

    var filterCountrySelect = document.querySelector('#filter-country select');
    if (filterCountrySelect) {
      filterCountrySelect.value = params.get('country') || '';
    }

    var filterTopicSelect = document.querySelector('#filter-topic select');
    if (filterTopicSelect) {
      filterTopicSelect.value = params.get('topic') || '';
    }

    var trainingInput = document.querySelector('#training-toggle input');
    if (trainingInput) {
      trainingInput.checked = !!params.get('training');
    }

    var recentInput = document.querySelector('#recent-toggle input');
    if (recentInput) {
      recentInput.checked = !!params.get('show_recent');
    }
  }

  function processFilter(params) {
    //remove legacy params
    params.delete('hub_id');

    //remove multi country to allow normal function of filters
    params.delete('multi_country');

    window.history.pushState("object or string", "Title", "?" + params.toString());

    if (tofinoJS.isFrontPage) {
      window.location.href = tofinoJS.homeUrl + '/search-groups/?' + params.toString()
    } else if(tofinoJS.postName === 'search-groups') {
      location.reload()
    } else {
      udpateFilterUi(params);

      map.removeLayer(clusterMarkers)
      map.removeLayer(hubMarkers)
      map.removeLayer(trainerMarkers)
      getMarkers(getMarkerParams())
    }
  }

  function checkForEvents() {
    //hide panel
    var closeBtn = document.querySelector('#map-iframe button.close');
    if (closeBtn) {
      closeBtn.addEventListener('click', function () {
        var panel = document.getElementById('map-info-panel');
        if (panel) panel.style.display = 'none';
      });
    }

    //go to my location
    var locationBtn = document.querySelector('#map-iframe button.my-location');
    if (locationBtn) {
      locationBtn.addEventListener('click', function () {
        map.locate({ setView: true, maxZoom: 8 });
      });
    }

    var filterHubSelect = document.querySelector('#filter-hub select');
    if (filterHubSelect) {
      filterHubSelect.addEventListener('change', function() {
        var url = new URL(window.location.href)
        var params = new URLSearchParams(url.search)

        //delete country from url and clear url
        params.delete('country')

        //update URL
        if (filterHubSelect.value.length) {
          params.set('hub_name', filterHubSelect.value);
        } else {
          params.delete('hub_name');
        }

        processFilter(params);
      });
    }

    var filterCountrySelect = document.querySelector('#filter-country select');
    if (filterCountrySelect) {
      filterCountrySelect.addEventListener('change', function() {
        var params = getUpdateParams();

        //delete country from url and clear url
        params.delete('hub_name')

        //update
        if (filterCountrySelect.value.length) {
          params.set('country', filterCountrySelect.value);
        } else {
          params.delete('country');
        }

        processFilter(params);
      });
    }

    var filterTopicSelect = document.querySelector('#filter-topic select');
    if (filterTopicSelect) {
      filterTopicSelect.addEventListener('change', function() {
        var params = getUpdateParams();

        console.log('change topic')

        //update
        if (filterTopicSelect.value.length) {
          params.set('topic', filterTopicSelect.value);
        } else {
          params.delete('topic');
        }

        processFilter(params);
      });
    }

    var filterTypeSelect = document.querySelector('#filter-type select');
    if (filterTypeSelect) {
      filterTypeSelect.addEventListener('change', function() {
        var params = getUpdateParams();

        if (filterTypeSelect.value.length) {
          params.set('type', filterTypeSelect.value);
        } else {
          params.delete('type');
        }

        processFilter(params)
      });
    }

    var trainingInput = document.querySelector('#training-toggle input');
    if (trainingInput) {
      trainingInput.addEventListener('change', function() {
        var params = getUpdateParams();

        if(this.checked) {
          params.set('training', true)
        } else {
          params.delete('training')
        }

        processFilter(params)
      });
    }

    var recentInput = document.querySelector('#recent-toggle input');
    if (recentInput) {
      recentInput.addEventListener('change', function() {
        var params = getUpdateParams();

        if(this.checked) {
          params.set('show_recent', true)
        } else {
          params.delete('show_recent')
        }

        processFilter(params)
      });
    }

    //back button hit
    window.onpopstate = function () {
      var params = getUpdateParams();

      processFilter(params);
    }; history.pushState({}, '');
  }

  //these variables are given global scope
  var map = initialiseMap()
  var clusterMarkers, hubMarkers, trainerMarkers;

  var params = getMarkerParams()

  //set params
  if (tofinoJS.postName === 'hub-list') {
    params['type'] ='3';
  }

  if (tofinoJS.postName === 'trainers') {
    params['type'] ='4';
  }

  getMarkers(params)

  checkForEvents()
}

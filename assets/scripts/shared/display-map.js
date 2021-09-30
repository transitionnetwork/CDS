var $ = window.jQuery;
import L from 'leaflet';
import 'leaflet.markercluster';
import 'leaflet.locatecontrol';
import * as GeoSearch from 'leaflet-geosearch';

export default function () {
  function getUpdateParams() {
    var url = new URL(window.location.href)
    var params = new URLSearchParams(url.search)

    return params;
  }

  function getMarkerParams() {
    var params = {}
    for (let p of getUpdateParams()) {
      console.log(p)
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

    // map.addControl(new L.Control.Search({
    //   url: 'https://nominatim.openstreetmap.org/search?format=json&q={s}',
    //   jsonpParam: 'json_callback',
    //   propertyName: 'display_name',
    //   propertyLoc: ['lat', 'lon'],
    //   marker: L.circleMarker([0, 0], { radius: 30 }),
    //   autoCollapse: true,
    //   autoType: false,
    //   minLength: 2,
    //   zoom: 13
    // }));

    L.control.locate({
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
          marker = L.marker([response.initiatives[i].lat, response.initiatives[i].lng], { icon: initiativeMarkerIcon });
          
          marker.bindPopup('<h5>' + response.initiatives[i].title + '</h5><div><a href="' + response.initiatives[i].permalink + '" target="_parent" class="btn btn-sm btn-primary">View</a></div>');
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
          
          marker.bindPopup('<h5>' + response.hubs[i].title + '</h5><div><a href="' + response.hubs[i].permalink + '" target="_parent" class="btn btn-sm btn-primary">View</a></div>');
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
          className: 'hub-cluster marker-cluster',
          iconSize: [40, 40]
        });
      }
    });
    
    for (const i in response.trainers) {
      if (response.trainers[i].lat && response.trainers[i].lng) {

        if (response.trainers[i].type === 'trainers') {
          marker = L.marker([response.trainers[i].lat, response.trainers[i].lng], { icon: trainerMarkerIcon });

          marker.bindPopup('<h5>' + response.trainers[i].title + '</h5><div><a href="' + response.trainers[i].permalink + '" target="_parent" class="btn btn-sm btn-primary">View</a></div>');
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
    var countHubs = Object.keys(response.hubs).length;
    var countTrainers = Object.keys(response.trainers).length;
    // var countTotal = countInitiatives + countHubs;
    
    $('.key .initiative .count').html('(' + countInitiatives + ')');
    $('.key .trainer .count').html('(' + countTrainers + ')');
    $('.key .hub .count, #filter-type span.hubs').html('(' + countHubs + ')');

    // $('#filter-type option[value="2"]').html('Initiatives (' + countInitiatives + ')')
    // $('#filter-type option[value="3"]').html('Hubs (' + countHubs + ')')
  }
  
  function getMarkers(params) {
    $('#map-loading').show();
    
    $.ajax({
      url: tofinoJS.ajaxUrl,
      type: 'POST',
      cache: false,
      data: {
        action: 'getMarkers',
        value: {
          params
        }
      },
      dataType: 'json',
      success: function (response) {
        console.log(response);

        $('#map-loading').hide();
        $('#map-no-results').hide();
        clusterMarkers = L.markerClusterGroup({ chunkedLoading: true });
        hubMarkers = L.markerClusterGroup({ chunkedLoading: true });
        trainerMarkers = L.markerClusterGroup({ chunkedLoading: true });
        
        if (Object.keys(response.initiatives).length > 0 || Object.keys(response.hubs).length > 0 || Object.keys(response.trainers).length > 0) {
          displayMarkers(response);
        } else {
          $('#map-no-results').show();
        }
      },
      error: function (jqxhr, status, exception) {
        console.log('JQXHR:', jqxhr);
        console.log('Status:', status);
        console.log('Exception:', exception);
      }
    })
  }

  function udpateFilterUi(params) {
    if(params.get('type')) {
      $('#filter-type select').val(params.get('type'))
    } else {
      $('#filter-type select').val('1');
    }
    
    if(params.get('hub_name')) {
      $('#filter-hub select').val(params.get('hub_name'))
    } else {
      $('#filter-hub select').val('');
    }
    
    if(params.get('country')) {
      $('#filter-country select').val(params.get('country'))
    } else {
      $('#filter-country select').val('');
    }
    
    if(params.get('training')) {
      $('#training-toggle input').prop('checked', true)
    } else {
      $('#training-toggle input').prop('checked', false)
    }
  }
  
  function processFilter(params) {
    //remove legacy params
    params.delete('hub_id');
    
    console.log(tofinoJS);

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
    $('#map-iframe button.close').on('click', function () {
      $('#map-info-panel').hide();
    })

    //go to my location
    $('#map-iframe button.my-location').on('click', function () {
      map.locate({ setView: true, maxZoom: 8 });
    })

    $('#filter-hub select').on('change', function() {
      var url = new URL(window.location.href)
      var params = new URLSearchParams(url.search)

      //delete country from url and clear url
      params.delete('country')
      
      //update URL
      if ($('#filter-hub select').val().length) {
        params.set('hub_name', $('#filter-hub select').val());
      } else {
        params.delete('hub_name');
      }

      processFilter(params);
    })

    $('#filter-country select').on('change', function() {
      var params = getUpdateParams();

      //delete country from url and clear url
      params.delete('hub_name')
      
      //update
      if ($('#filter-country select').val().length) {
        params.set('country', $('#filter-country select').val());
      } else {
        params.delete('country');
      }

      processFilter(params);
    })

    $('#filter-type select').on('change', function() {
      var params = getUpdateParams();

      if ($('#filter-type select').val().length) {
        params.set('type', $('#filter-type select').val());
      } else {
        params.delete('type');
      }

      processFilter(params)
    })
    
    $('#training-toggle input').on('change', function() {
      var params = getUpdateParams();
      
      if(this.checked) {
        params.set('training', true)
        // if(params.get('type') === '2') {
        //   params.set('type', '1')
        // }
      } else {
        params.delete('training')
      }
      
      processFilter(params)
    })

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

  getMarkers(params)
  
  checkForEvents()
}

var $ = window.jQuery;
import L from 'leaflet';
import 'leaflet.markercluster';
import 'leaflet.locatecontrol';

export default function () {

  function transformToAssocArray(prmstr) {
    var params = {};
    var prmarr = prmstr.split("&");
    for (var i = 0; i < prmarr.length; i++) {
      var tmparr = prmarr[i].split("=");
      params[tmparr[0]] = tmparr[1];
    }
    return params;
  }

  function getSearchParams() {
    var prmstr = window.location.search.substr(1);
    return prmstr != null && prmstr != "" ? transformToAssocArray(prmstr) : {};
  }
  
  function initialiseMap() {
    map = L.map('iframe_map', { "tap": false }).setView([0, 0], 3);

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

    return map
  }

  function displayMarkers(response) {
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
    clusterMarkers = L.markerClusterGroup({ chunkedLoading: true });
    hubMarkers = L.markerClusterGroup({ chunkedLoading: true });

    console.log(response)

    for (const i in response.initiatives) {
      if (response.initiatives[i].lat && response.initiatives[i].lng) {
        
        if(response.initiatives[i].type === 'initiative') {
          marker = L.marker([response.initiatives[i].lat, response.initiatives[i].lng], { icon: initiativeMarkerIcon });
          
          marker.bindPopup('<h5>' + response.initiatives[i].title + '</h5><div><a href="' + response.initiatives[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
          clusterMarkers.addLayer(marker);
          
          range.push([response.initiatives[i].lat, response.initiatives[i].lng]);
        }
      }
    }

    map.addLayer(clusterMarkers);

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

        if (response.hubs[i].type === 'hub') {
          marker = L.marker([response.hubs[i].lat, response.hubs[i].lng], { icon: hubMarkerIcon });
          
          marker.bindPopup('<h5>' + response.hubs[i].title + '</h5><div><a href="' + response.hubs[i].permalink + '" target="_blank" class="btn btn-sm btn-primary">View</a></div>');
          hubMarkers.addLayer(marker);
          
          range.push([response.hubs[i].lat, response.hubs[i].lng]);
        }
      }
    }
    
    map.addLayer(hubMarkers);

    var bounds = L.latLngBounds(range);
    map.fitBounds(bounds);

    //add counts
    var countInitiatives = Object.keys(response.initiatives).length;
    var countHubs = Object.keys(response.hubs).length;
    // var countTotal = countInitiatives + countHubs;
    
    $('.key .initiative .count').html('(' + countInitiatives + ')');
    $('.key .hub .count, #filter-type span.hubs').html('(' + countHubs + ')');

    // $('#filter-type option[value="2"]').html('Initiatives (' + countInitiatives + ')')
    // $('#filter-type option[value="3"]').html('Hubs (' + countHubs + ')')
  }
  
  function getMarkers(params) {
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
        $('.map-loading').hide();
        $('#map-panel').show();
        if(typeof response.initiatives === 'object'  || typeof response.hubs === 'object') {
          displayMarkers(response);
        } else {
          console.log('no results')
        }
      },
      error: function (jqxhr, status, exception) {
        console.log('JQXHR:', jqxhr);
        console.log('Status:', status);
        console.log('Exception:', exception);
      }
    })
  }

  function processFilter(params) {
    window.history.pushState("object or string", "Title", "?" + params.toString());

    map.removeLayer(clusterMarkers)
    map.removeLayer(hubMarkers)
    getMarkers(getSearchParams())

    if(tofinoJS.postTitle === 'example') {
      console.log(tofinoJS);
    }
  }
  
  function checkForEvents() {
    //hide panel
    $('#iframe_map button.close').on('click', function () {
      $(this).closest('#map-panel').hide();
    })

    //go to my location
    $('#iframe_map button.my-location').on('click', function () {
      map.locate({ setView: true, maxZoom: 12 });
    })

    $('#filter-hub select').on('change', function() {
      var url = new URL(window.location.href)
      var params = new URLSearchParams(url.search)

      //delete country from url and clear url
      params.delete('country')
      $('#filter-country select').val('');
      
      //update URL
      if ($('#filter-hub select').val().length) {
        params.set('hub_name', $('#filter-hub select').val());
      } else {
        params.delete('hub_name');
      }

      processFilter(params);
    })

    $('#filter-country select').on('change', function() {
      var url = new URL(window.location.href)
      var params = new URLSearchParams(url.search)

      //delete country from url and clear url
      params.delete('hub_name')
      $('#filter-hub select').val('');
      
      //update
      if ($('#filter-country select').val().length) {
        params.set('country', $('#filter-country select').val());
      } else {
        params.delete('country');
      }

      processFilter(params);
    })

    $('#filter-type select').on('change', function() {
      var url = new URL(window.location.href)
      var params = new URLSearchParams(url.search)

      if ($('#filter-type select').val().length) {
        params.set('type', $('#filter-type select').val());
      } else {
        params.delete('type');
      }

      processFilter(params)
    })
    
    $('#training-toggle input').on('change', function() {
      var url = new URL(window.location.href)
      var params = new URLSearchParams(url.search)
      
      if(this.checked) {
        params.set('training', true)
        if(params.get('type', '2')) {
          params.set('type', '1')
        }
      } else {
        params.delete('training')
      }
      
      processFilter(params)
    })
  }

  //these variables are given global scope
  var map = initialiseMap()
  var clusterMarkers, hubMarkers;
  
  getMarkers(getSearchParams())

  checkForEvents()
}

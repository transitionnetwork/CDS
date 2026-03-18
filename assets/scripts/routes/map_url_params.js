import displayMap from '../shared/display-map';

export default {
  loaded() {
    const queryString = window.location.search;

    const urlParams = new URLSearchParams(queryString);
    const map_type = urlParams.get('map_type');
    const hub_name = urlParams.get('hub_name');
    const country = urlParams.get('country');
    const training = urlParams.get('training');
    const search = urlParams.get('s');

    var formData = new URLSearchParams();
    formData.append('action', 'getMarkers');
    if (map_type) formData.append('value[map_type]', map_type);
    if (hub_name) formData.append('value[hub_name]', hub_name);
    if (country) formData.append('value[country]', country);
    if (training) formData.append('value[training]', training);
    if (search) formData.append('value[search]', search);

    fetch(tofinoJS.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: formData.toString()
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      console.log(response);
      var mapPanel = document.getElementById('map-panel');
      if (mapPanel) mapPanel.style.display = '';
      displayMap(response);
    })
    .catch(function(err) {
      console.log('Error:', err);
    });

  }
};

// We need jQuery
var $ = window.jQuery;
import displayMap from '../shared/map';
import displayBarGraph from '../shared/graph-all';

export default {
  loaded() {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);

    if(urlParams.get('tab') == 'file') {
      $('#nav-filesharing-tab').tab('show')
    }

    displayBarGraph();
    // displayMap();
  }
};

import displayMap from '../shared/map';
import displayBarGraph from '../shared/graph-all';

export default {
  loaded() {
    displayBarGraph();
    displayMap();
  }
};

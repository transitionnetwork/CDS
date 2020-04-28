import displayMap from '../shared/map';
import displayBarGraph from '../shared/plotly';

export default {
  loaded() {
    displayBarGraph();
    displayMap();
  }
};

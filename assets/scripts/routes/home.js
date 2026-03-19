import displayMap from '../shared/display-map';

export default {
  loaded() {
    const queryString = window.location.search;
    console.log(queryString);

    displayMap();
  },
  finalize() {
  }
};

import displayMap from '../shared/display-map';
// import select2 from '../shared/select2';

export default {
  loaded() {
    const queryString = window.location.search;
    console.log(queryString);

    displayMap();
  },
  finalize() {
    // select2();
  }
};

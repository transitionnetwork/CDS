// We need jQuery
var $ = window.jQuery;

import singleMap from '../shared/single-map';
import select2 from '../shared/select2';

export default {
  loaded() {
    if($('select').length) {
      select2();
    }
    
    singleMap()
  }
}

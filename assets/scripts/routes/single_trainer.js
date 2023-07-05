// We need jQuery
var $ = window.jQuery;

import singleMap from '../shared/single-map';

export default {
  loaded() {
    if (document.getElementById('single-map') !== null) {
      singleMap();
    }

    //populate trainer email form
    const name = $('#trainer-name').data('name');
    const email = $('#trainer-email').data('email');

    $('input[name="trainer-name"]').val(name);
    $('input[name="trainer-email"]').val(email);
  }
}

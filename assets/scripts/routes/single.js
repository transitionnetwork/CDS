// We need jQuery
var $ = window.jQuery;

import singleMap from '../shared/single-map';
import select2 from '../shared/select2';

export default {
  loaded() {
    if($('select').length) {
      select2();
    }
    
    if (document.getElementById('single-map') !== null) {
      singleMap();
    }

    //populate grouop email form
    const name = $('#group-name').data('name');
    const email = $('#group-email').data('email');

    $('input[name="group-name"]').val(name);
    $('[name="group-email"]').val(email);

    document.getElementById('token-login-button').addEventListener('click', () => {
      const text = document.getElementById('token-login-link').innerHTML;
      navigator.clipboard.writeText(text);
      console.log('copied to clipboard')
    });
  }
}

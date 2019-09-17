// We need jQuery
var $ = window.jQuery;

export default {
  loaded() {
    $('select').select2({
      width: '100%'
    });
  }
}

export default {
  loaded() {
    window.onbeforeunload = function (e) {
      e.preventDefault();
      return 'Are you sure you want to leave?';
    };
  }
}

import singleMap from '../shared/single-map';

export default {
  loaded() {
    if (document.getElementById('single-map') !== null) {
      singleMap();
    }

    //populate trainer email form
    var trainerNameEl = document.getElementById('trainer-name');
    var trainerEmailEl = document.getElementById('trainer-email');

    var name = trainerNameEl ? trainerNameEl.dataset.name : '';
    var email = trainerEmailEl ? trainerEmailEl.dataset.email : '';

    // Set defaultValue too: CF7 calls form.reset() on load when WP_CACHE is on
    // (LiteSpeed on prod), which would otherwise wipe these values.
    var nameInput = document.querySelector('input[name="trainer-name"]');
    if (nameInput) nameInput.value = nameInput.defaultValue = name;

    var emailInput = document.querySelector('input[name="trainer-email"]');
    if (emailInput) emailInput.value = emailInput.defaultValue = email;
  }
}

import singleMap from '../shared/single-map';

export default {
  loaded() {
    if (document.getElementById('single-map') !== null) {
      singleMap();
    }

    //populate group email form
    var groupNameEl = document.getElementById('group-name');
    var groupEmailEl = document.getElementById('group-email');

    var name = groupNameEl ? groupNameEl.dataset.name : '';
    var email = groupEmailEl ? groupEmailEl.dataset.email : '';

    // Set defaultValue too: CF7 calls form.reset() on load when WP_CACHE is on
    // (LiteSpeed on prod), which would otherwise wipe these values.
    var nameInput = document.querySelector('input[name="group-name"]');
    if (nameInput) nameInput.value = nameInput.defaultValue = name;

    var emailInput = document.querySelector('[name="group-email"]');
    if (emailInput) emailInput.value = emailInput.defaultValue = email;

    var tokenLoginButton = document.getElementById('token-login-button');
    if (tokenLoginButton) {
      tokenLoginButton.addEventListener('click', () => {
        const text = document.getElementById('token-login-link').innerHTML;
        navigator.clipboard.writeText(text);
      });
    }
  }
}

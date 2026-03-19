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

    console.log(name);

    var nameInput = document.querySelector('input[name="group-name"]');
    if (nameInput) nameInput.value = name;

    var emailInput = document.querySelector('[name="group-email"]');
    if (emailInput) emailInput.value = email;

    document.getElementById('token-login-button').addEventListener('click', () => {
      const text = document.getElementById('token-login-link').innerHTML;
      navigator.clipboard.writeText(text);
      console.log('copied to clipboard')
    });
  }
}

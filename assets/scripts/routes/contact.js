import ajaxForm from '../ajax-form';

export default {
  init() {
    // JavaScript to be fired on contact page
    var contactForm = document.querySelector('.contact-form');
    if (contactForm) {
      ajaxForm(contactForm);
    }
  },
  loaded() {
    // Javascript to be fired on page once fully loaded
  }
};

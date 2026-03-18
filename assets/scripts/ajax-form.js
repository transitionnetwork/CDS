/**
 * Vanilla JS ajax form handler — replaces the jQuery plugin.
 * Self-initializes on all forms with class `.ajax-form` or attribute `[data-wp-action]`.
 */
function ajaxForm(formEl, options) {
  var submitActor = null;
  var submitActors = formEl.querySelectorAll('[type="submit"]');

  var defaults = {
    responseDiv: '.js-form-result',
    action: formEl.dataset.wpAction,
    btnProgressText: 'Wait..',
    hideFormAfterSucess: true,
    beforeSerializeData: function() {},
    beforeRedirect: function() {},
    afterSuccess: function() {},
    afterError: function() {}
  };

  var opts = Object.assign({}, defaults, options);

  // Resolve responseDiv to an element
  var responseDivEl = (typeof opts.responseDiv === 'string')
    ? document.querySelector(opts.responseDiv)
    : opts.responseDiv;

  formEl.addEventListener('submit', function(e) {
    e.preventDefault();

    if (submitActor === null) {
      submitActor = submitActors[0] || null;
    }

    if (formEl.classList.contains('submitting')) {
      return false;
    }

    formEl.classList.add('submitting');

    opts.beforeSerializeData();

    var serializedData = new URLSearchParams(new FormData(formEl)).toString();
    var btnOrgText = submitActor ? submitActor.textContent : '';

    // Disable all inputs
    formEl.querySelectorAll('input, select, textarea, button').forEach(function(el) {
      el.disabled = true;
    });

    if (submitActor) {
      submitActor.textContent = opts.btnProgressText;
    }

    var postData = new URLSearchParams();
    postData.append('action', opts.action);
    postData.append('data', serializedData);
    postData.append('nextNonce', tofinoJS.nextNonce);

    fetch(tofinoJS.ajaxUrl, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: postData.toString()
    })
    .then(function(res) { return res.json(); })
    .then(function(response) {
      formEl.classList.remove('submitting');

      if (response.success === true) {
        if (response.redirect) {
          opts.beforeRedirect();
          window.location = response.redirect;
          return;
        }

        if (responseDivEl) {
          responseDivEl.classList.remove('alert-danger');
          responseDivEl.classList.add('alert', 'alert-success');
          responseDivEl.innerHTML = response.message;
        }

        // Reset fields
        formEl.querySelectorAll('input, select, textarea').forEach(function(el) {
          if (el.type !== 'submit' && el.type !== 'button' && el.type !== 'reset') {
            el.value = '';
          }
        });

        if (submitActor) submitActor.textContent = btnOrgText;

        if (opts.hideFormAfterSucess === true) {
          formEl.style.display = 'none';
        } else {
          formEl.querySelectorAll('input, select, textarea, button').forEach(function(el) {
            el.disabled = false;
          });
        }

        opts.afterSuccess();
      } else {
        if (responseDivEl) {
          responseDivEl.classList.add('alert', 'alert-danger');
          responseDivEl.innerHTML = response.message;
        }

        // Re-enable fields
        formEl.querySelectorAll('input, select, textarea, button').forEach(function(el) {
          el.disabled = false;
        });

        if (submitActor) submitActor.textContent = btnOrgText;

        // Remove any existing failed validation classes
        formEl.querySelectorAll('.form-control-danger').forEach(function(el) {
          el.classList.remove('form-control-danger');
        });
        formEl.querySelectorAll('.has-danger').forEach(function(el) {
          el.classList.remove('has-danger');
        });

        // Mark all non-button, non-checkbox inputs as valid
        formEl.querySelectorAll('input, select, textarea').forEach(function(el) {
          if (el.type !== 'button' && el.type !== 'submit' && el.type !== 'reset' && el.type !== 'checkbox') {
            el.classList.add('is-valid');
            var formGroup = el.closest('.form-group');
            if (formGroup) formGroup.classList.add('has-success');
          }
        });

        formEl.querySelectorAll('input[type="checkbox"]').forEach(function(el) {
          var checkboxWrap = el.closest('.checkbox');
          if (checkboxWrap) checkboxWrap.classList.add('has-success');
        });

        if (response.type === 'validation') {
          var invalidFields;
          try {
            invalidFields = (typeof response.extra === 'string') ? JSON.parse(response.extra) : response.extra;
          } catch (e) {
            invalidFields = {};
          }

          var msgHTML = '';

          for (var key in invalidFields) {
            if (invalidFields.hasOwnProperty(key)) {
              var value = invalidFields[key];
              msgHTML += '<li>' + value + '</li>';

              var field = formEl.querySelector('[name=' + key + ']');
              if (field) {
                field.classList.remove('form-control-success', 'is-valid');
                field.classList.add('is-invalid');

                if (field.type === 'checkbox') {
                  var checkboxWrap = field.closest('.checkbox');
                  if (checkboxWrap) {
                    checkboxWrap.classList.remove('has-success');
                    checkboxWrap.classList.add('has-danger');
                  }
                }
              }
            }
          }

          if (responseDivEl) {
            responseDivEl.insertAdjacentHTML('beforeend', '<ul>' + msgHTML + '</ul>');
          }
        }

        opts.afterError();
      }
    })
    .catch(function() {
      if (responseDivEl) {
        responseDivEl.classList.add('alert', 'alert-danger');
        responseDivEl.innerHTML = 'An error occured.';
      }
      formEl.querySelectorAll('input, select, textarea, button').forEach(function(el) {
        el.disabled = false;
      });
      if (submitActor) submitActor.textContent = btnOrgText;
      formEl.classList.remove('submitting');
    });
  });

  // Track which submit button was clicked
  submitActors.forEach(function(btn) {
    btn.addEventListener('click', function() {
      submitActor = this;
    });
  });
}

// Self-initialize on all matching forms
document.addEventListener('DOMContentLoaded', function() {
  document.querySelectorAll('.ajax-form, [data-wp-action]').forEach(function(form) {
    ajaxForm(form);
  });
});

export default ajaxForm;

import TomSelect from 'tom-select';

export default function() {
  document.querySelectorAll('select:not(.tomselected)').forEach(function(el) {
    new TomSelect(el, {
      allowEmptyOption: true,
    });
  });
}

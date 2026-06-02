<?php
/**
 * ACF field hooks — load / prepare / validate field definitions and rendering.
 *
 * Save-time logic lives in acf-save.php; this file is for hooks that shape how
 * fields are defined or displayed.
 */

// Change label of Content Editor in acf_form()
function prepare_post_content($field) {
  $field['label'] = "Description";
  return $field;
}
add_filter('acf/prepare_field/name=_post_content', 'prepare_post_content');

// Disable wysiwyg fancies on the content editor.
function change_post_content_type($field) {
  if($field['type'] == 'wysiwyg') {
    $field['tabs'] = 'visual';
    $field['toolbar'] = 'basic';
    $field['media_upload'] = 0;
  }
  return $field;
}
add_filter('acf/get_valid_field', 'change_post_content_type');

// Set default hub value to no-hub when adding an initiative.
function set_tax_default($field) {
  global $post;
  if($post && $post->post_name == 'add-new-group') {
    $field['value'] = 285;
  }
  return $field;
}
add_filter('acf/load_field/key=field_5c473dfca1fd3', 'set_tax_default');

// Hide the greylist field on the front-end group edit screen, EXCEPT when the
// group is in a greylist country and hasn't supplied the info yet. This lets us
// retrospectively collect greylist info (e.g. when a country is added to the
// greylist after the group was created) without re-prompting compliant groups,
// whose uploads have already been offloaded to Dropbox and cleared.
add_filter('acf/prepare_field', 'my_acf_prepare_field', 10, 1);
function my_acf_prepare_field($field) {
  if ( ! is_admin() && $field['key'] == 'field_695e763a53af4' && is_page('edit-group')) {
    $edit_post_id = get_query_var('edit_post');

    $outstanding = $edit_post_id
      && is_group_in_greylist($edit_post_id)
      && ! get_post_meta($edit_post_id, 'greylist_info_provided', true);

    if ( ! $outstanding ) {
      return false;
    }
  }
  return $field;
}

// Drive the greylist field's show/hide from the stored Greylist Countries
// (Site Options > Greylist), so it stays in sync with is_group_in_greylist().
add_filter('acf/load_field/key=field_695e763a53af4', 'load_greylist_conditional_logic');
function load_greylist_conditional_logic($field) {
  $greylist = get_field('greylist_greylist_countries', 'options') ?: array();
  $field['conditional_logic'] = array_map(function($term_id) {
    return array(array(
      'field'    => 'field_618ea05c4bd16', // country field on the group edit form
      'operator' => '==contains',
      'value'    => (string) $term_id,
    ));
  }, $greylist);
  return $field;
}

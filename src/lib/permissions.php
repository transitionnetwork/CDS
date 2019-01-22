<?php
function is_user_role($queried_role)
{
  $user_roles = wp_get_current_user()->roles;
  if (in_array($queried_role, $user_roles)) {
    return true;
  }
}

function is_post_in_user_hub($post) {
  $post_hub_id = (get_field('hub_tax', $post->ID));
  $user_hub_ids = get_the_terms(wp_get_current_user(), 'hub');
  foreach($user_hub_ids as $user_hub_id) {
    if($post_hub_id == $user_hub_id->term_id) {
      return true;
    }
  }
  return false;
}

function is_user_post_author($post) {
  if(get_current_user_id() == get_the_author_id($post)) {
    return true;
  }
}

function can_view_any_healthcheck() {
  if (is_user_logged_in()) {
    return true;
  } else {
    return false;
  }
}
function can_view_healthcheck($post)
{
  if(is_user_role('administrator') || is_user_role('super_hub')) {
    return true;
  }
  if(is_user_role('hub') && is_post_in_user_hub($post)) {
    return true;
  }
  if(is_user_role('initiative') && is_user_post_author($post)) {
    return true;
  }
  return false;
}

function can_write_healthcheck($post) {
  //same as view for now
  return can_view_healthcheck($post);
}

function can_write_initiative($post) {
  if (is_user_role('administrator') || is_user_role('super_hub')) {
    return true;
  }
  if (is_user_role('hub') && is_post_in_user_hub($post)) {
    return true;
  }
  if (is_user_role('initiative') && is_user_post_author($post)) {
    return true;
  }
  return false;
}


function can_publish_initiative($post) {
  if (is_user_role('administrator') || is_user_role('super_hub')) {
    return true;
  }
  if (is_user_role('hub') && is_post_in_user_hub($post)) {
    return true;
  }
  return false;
}

function is_post_published($post) {
  if(get_post_status($post->ID) == 'publish') {
    return true;
  }
  return false;
}

// Publish
function show_publish_button($post_id)
{
  global $post;
  echo '<form name="front_end_publish" method="POST" action="">
    <input type="hidden" name="pid" id="pid" value="' . $post_id . '">
    <input type="hidden" name="FE_PUBLISH" id="FE_PUBLISH" value="FE_PUBLISH">
    <input type="submit" name="submit" id="submit" value="Approve Post">
  </form>';
}

//function to update post status
function change_post_status($post_id, $status)
{
  $current_post = get_post($post_id, 'ARRAY_A');
  $current_post['post_status'] = $status;
  wp_update_post($current_post);
}

function check_publish_argument() {
  if (isset($_POST['FE_PUBLISH']) && $_POST['FE_PUBLISH'] == 'FE_PUBLISH') {
    if (isset($_POST['pid']) && !empty($_POST['pid'])) {
      change_post_status((int)$_POST['pid'], 'publish');
    }
  }
}
add_action('init', 'check_publish_argument');

<?php
function is_user_role($queried_roles, $user = null)
{
  //will accept array or string as first parameter
  
  if(!$user) {
    $user = wp_get_current_user();
  }
  
  $user_roles = $user->roles;
  
  if(is_array($queried_roles)) {
    foreach($queried_roles as $queried_role) {
      if (in_array($queried_role, $user_roles)) {
        return true;
      }
    }
  } else {
    if (in_array($queried_roles, $user_roles)) {
      return true;
    }
  }

  return false;
}

function is_post_in_user_hub($post) {
  $post_hub_id = get_the_terms($post, 'hub')[0]->term_id;
  $user_hub_ids = get_user_meta(wp_get_current_user()->ID, 'hub_user');

  foreach($user_hub_ids as $user_hub_id) {
    if($post_hub_id == $user_hub_id) {
      return true;
    }
  }
  return false;
}

function is_user_post_author($post) {
  if(get_current_user_id() === (int)get_post_field( 'post_author', $post )) {
    return true;
  }
  return false;
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
  // same as can_write_initiative for now
  return can_write_initiative($post);
}

function can_write_healthcheck($post) {
  //same as view for now
  return can_view_healthcheck($post);
}

function can_write_initiative($post) {
  if (is_user_role('administrator') || is_user_role('super_hub')) {
    return true;
  }
  if (is_user_role('hub') && (is_post_in_user_hub($post) || is_user_post_author($post))) {
    return true;
  }
  if (is_user_role('initiative') && is_user_post_author($post)) {
    return true;
  }

  //co_author perm checking
  if (ma_is_co_author($post->ID)) {
    return true;
  }

  if (is_user_role('tt_hub')) {
    $tt_hub_ids = get_tt_hub_ids();

    $post_hub_id = get_the_terms($post, 'hub')[0]->term_id; 

    if(in_array($post_hub_id, $tt_hub_ids)) {
      return true;
    }

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

function can_edit_hub($term_id) {
  if(is_user_role(array('super_hub', 'administrator'))) {
    return true;
  }
  
  $user_hub = get_field('hub_user', wp_get_current_user());
  if((int)$user_hub === (int)$term_id) {
    return true;
  } 
  return false;
}

//function to update post status
function change_post_status($post_id, $status)
{
  $current_post = get_post($post_id, 'ARRAY_A');
  $current_post['post_status'] = $status;
  wp_update_post($current_post);
}

function check_post() {
  //request super hub access in dashboard
  if (isset($_POST['type']) && $_POST['type'] == 'request_hub_access') {
    if (isset($_POST['user_id']) && !empty($_POST['user_id'])) {
      $user_id = (int)$_POST['user_id'];
      $success = custom_email_send_access_request_to_hub($user_id);
      
      if($success) {
        $date = date('Y-m-d H:i:s');
        update_user_meta($user_id, 'hub_access_requested', $date);
  
        $redirect_url = add_query_arg('updated', 'hub_request', home_url('account'));
      } else {
        $redirect_url = add_query_arg('failed', 'hub_request', home_url('account'));
      }
      
      wp_safe_redirect($redirect_url);
      exit();
    }
  }
}
add_action('template_redirect', 'check_post');

function is_my_trainer_post($post = null) {
  $post_id = (!$post) ? get_post()->ID : (int)$post;

  $author_id = (int)get_post_field ('post_author', $post_id);

  if($author_id === get_current_user_id()) {
    return true;
  }

  return false;
}

function get_tt_hub_ids() {
  return array(
    284,
    800,
    287
  );
}

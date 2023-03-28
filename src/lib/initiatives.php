<?php
function get_initiatives_main() {
  $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  $per_page = 50;

  $args = array(
    'post_type' => 'initiatives',
    'orderby' => 'modified',
    'order' => 'DESC',
    'paged' => $paged,
    'posts_per_page' => $per_page
  );

  if(get_query_var('hub_name')) {
    $hub_query = array (
      'taxonomy' => 'hub',
      'field' => 'slug',
      'terms' => get_query_var('hub_name')
    );
  } else {
    $hub_query = '';
  }

  if(get_query_var('country')) {
    $country_query =  array (
      'taxonomy' => 'country',
      'field' => 'slug',
      'terms' => get_query_var('country')
    );
  } else {
    $country_query = '';
  }

  if(get_query_var('country') || get_query_var('hub_name')) {
    $args['tax_query'] = array(
      'relation' => 'AND',
      $hub_query,
      $country_query
    );
  }

  if(get_query_var('s')) { 
    $args['s'] = get_query_var('s');
  }

  return new WP_Query($args);
}


function get_initiatve_age($post = 0) {
  $post = get_post( $post );
  
  $today = (int)date('U');
  $posted = get_the_modified_date('U', $post);
  $duration = $today - $posted;

  return floor($duration / 86400);
}

function author_access_request($post_id) {
  $author_requests = get_post_meta( $post_id, 'author_requests', true);
  if(!$author_requests) $author_requests = array();
  
  $author_requests[] = get_current_user_id();
  $author_requests = array_unique($author_requests);
  update_post_meta($post_id, 'author_requests', $author_requests);
  //TODO: Alert author
}

function author_access_is_requested($post_id) {
  $author_requests = get_post_meta( $post_id, 'author_requests', true);
  if(in_array(get_current_user_id(), $author_requests)) {
    return true;
  }

  return false;
}

function author_access_grant($post_id, $user_id) {
  //no idea yet cos plugin not installed
  //TODO: Add plugin code hook
  //TODO: Alert user
  var_dump('granted');
}

function author_access_deny($post_id, $user_id) {
  $author_requests = get_post_meta( $post_id, 'author_requests', true);

  if (($key = array_search($user_id, $author_requests)) !== false) {
    unset($author_requests[$key]);
  }

  update_post_meta($post_id, 'author_requests', $author_requests);
}

function author_access_remove($post_id, $user_id) {
  //TODO: ADD A BUTTON ON MAIN AUTHORS DASHBOARD OR ARTICLE WITH REVOKE ACCESS POWER
  var_dump('revoked;')
}

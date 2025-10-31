<?php
function get_initiatives_main() {
  if(is_front_page()) {
    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
  } else {
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
  }
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

  if(get_query_var('topic')) {
    $args['meta_query'][] = array('key' => 'group_detail_live_projects', 'compare' => 'LIKE', 'value' => get_query_var('topic'));
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
  
  $days = floor($duration / 86400);

  $opacity = '0.3';
  $opacity = ($days < 365 * 4) ? '0.45' : $opacity;
  $opacity = ($days < 365 * 3) ? '0.6' : $opacity;
  $opacity = ($days < 365 * 2) ? '0.8' : $opacity;
  $opacity = ($days < 365 * 1) ? '1' : $opacity;
  
  return array(
    'days' => $days,
    'opacity' => $opacity,
    'modified' => get_the_modified_date('Y-m-d H:i:s')
  );
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
  if(is_array($author_requests) && in_array(get_current_user_id(), $author_requests)) {
    return true;
  }

  return false;
}

function author_access_grant($post_id, $user_id) {
  //no idea yet cos plugin not installed
  //TODO: move id from 'author_requests' post meta into 'coauthors'
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
  var_dump('revoked;');
}

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

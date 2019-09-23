<?php
function return_map_markers() {
  //initiatives
  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1
  );

  if(!empty($_POST['value']['hub_name'])) {
    $args['tax_query'] = array(
      array (
        'taxonomy' => 'hub',
        'field' => 'slug',
        'terms' => $_POST['value']['hub_name']
      )
    );
  }

  if(!empty($_POST['value']['country'])) {
    $args['tax_query'] =  array(
      array (
        'taxonomy' => 'country',
        'field' => 'slug',
        'terms' => $_POST['value']['country']
      )
    );
  }

  if(!empty($_POST['value']['search'])) {
    $args['s'] = $_POST['value']['search'];
  }

  $query_string = serialize($args);
  $hash = 'map_' . md5($query_string);

  $posts = get_posts($args);


  if ( false === ( $i_markers = get_transient($hash))) {
    $key = 0;
    foreach($posts as $post) {
      $map = get_field('map', $post->ID);
      if(!empty($map['markers'])) {
        $i_markers[$key]['lat'] = $map['lat'];
        $i_markers[$key]['lng'] = $map['lng'];
        $i_markers[$key]['permalink'] = get_the_permalink($post->ID);
        $i_markers[$key]['title'] = get_the_title($post->ID);
        // $markers[$key]['excerpt'] = get_the_excerpt($post->ID);
        $key++;
      }
    }
	// Put the results in a transient. Expire after 12 hours.
  set_transient($hash, $i_markers, 12 * HOUR_IN_SECONDS );
  }

  //hubs
  global $sitepress;
  $sitepress->switch_lang('en');
  $hubs = get_terms('hub', array(
    'hide_empty' => false
  ));

  if($_POST['value']['hub_name']) {
    $hubs = []; //must be array
    $hubs[] = get_term_by('slug', $_POST['value']['hub_name'], 'hub');
  }
  
  //TODO add hub transient in here
  $key = 0;
  foreach($hubs as $hub) {
    $map = get_field('map', $hub);
    if(!empty($map['markers'])) {
      $h_markers[$key]['lat'] = $map['lat'];
      $h_markers[$key]['lng'] = $map['lng'];
      $h_markers[$key]['permalink'] = get_term_link($hub);
      $h_markers[$key]['title'] = $hub->name;
      $key ++;
    }
  }

  if($i_markers) {
    $data['i_markers'] = $i_markers;
  }

  if($h_markers) {
    $data['h_markers'] = $h_markers;
  }

  echo json_encode($data);
  wp_die();
}

add_action('wp_ajax_nopriv_getMapMarkers', 'return_map_markers');
add_action('wp_ajax_getMapMarkers', 'return_map_markers');

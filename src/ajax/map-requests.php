<?php
function get_i_data() {
  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1,
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

  
  $data = array();
  
  $posts = get_posts($args);
  
  if($posts) {
    $key = 0;
    foreach($posts as $initiative) {
      $map = get_field('map', $initiative->ID);
      if(!empty($map['markers'])) {
        $data[$key]['lat'] = $map['lat'];
        $data[$key]['lng'] = $map['lng'];
        $data[$key]['permalink'] = parse_post_link($initiative->ID);
        $data[$key]['title'] = get_the_title($initiative->ID);

        $key ++;
      }
    }
  }

  return $data;
}

function get_h_data() {
  $hubs = get_terms('hub', array(
    'hide_empty' => false
  ));

  if($_POST['value']['hub_name']) {
    $hubs = []; //must be array
    $hubs[] = get_term_by('slug', $_POST['value']['hub_name'], 'hub');
  }
  
  $key = 0;
  foreach($hubs as $hub) {
    $map = get_field('map', $hub);
    if(!empty($map['markers'])) {
      $data[$key]['lat'] = $map['lat'];
      $data[$key]['lng'] = $map['lng'];
      $data[$key]['permalink'] = get_term_link($hub);
      $data[$key]['title'] = $hub->name;
      $data[$key]['training'] = get_field('training', $hub);
      
      $key ++;
    }
  }

  return $data;
}


function return_map_markers() {

  if (!file_exists(TEMPLATEPATH . '/cache')) {
    mkdir(TEMPLATEPATH . '/cache', 0755, true);
  }

  $cache_expiry = 24 * 60 * 60; //24hrs

  $hub_name = $_POST['value']['hub_name'];
  if(is_array($hub_name)) {
    $hub_name = implode('_', $hub_name);
  }
  
  $i_filepath = TEMPLATEPATH . '/cache/i-cache_h' . $hub_name . '_c' . $_POST['value']['country'] . '.json';
  $h_filepath = TEMPLATEPATH . '/cache/h-cache_h' . $hub_name . '_c' . $_POST['value']['country'] . '.json';

  if(!file_exists($i_filepath) || filemtime($i_filepath) < time() - $cache_expiry ) {
    //file cache has expired
    $i_markers = get_i_data();
    file_put_contents($i_filepath, json_encode($i_markers));
    $data['i_status'] = 'queried';
  } else {
    $data['i_status'] = 'cached';
    $i_markers = json_decode(file_get_contents($i_filepath), true);
  }
  
  if(!file_exists($h_filepath) || filemtime($h_filepath) < time() - $cache_expiry) {
    //file cache has expired
    $h_markers = get_h_data();
    file_put_contents($h_filepath, json_encode($h_markers));
    $data['h_status'] = 'queried';
  } else {
    $h_markers = json_decode(file_get_contents($h_filepath), true);
    $data['h_status'] = 'cached';
  }

  if($i_markers) {
    $data['i_markers'] = $i_markers;
  }

  if($h_markers) {
    $data['h_markers'] = $h_markers;
  }

  $data['values_submitted'] = $_POST['value'];

  //debug
  // $data['i_filepath'] = $i_filepath;
  // $data['h_filepath'] = $h_filepath;

  echo json_encode($data);
  wp_die();
}

add_action('wp_ajax_nopriv_getMapMarkers', 'return_map_markers');
add_action('wp_ajax_getMapMarkers', 'return_map_markers');


function return_hub_markers() {
  $markers = get_h_data();

  if($_POST['value']['training'] === "true") {
    $training_markers = array();
    foreach($markers as $marker) {
      if($marker['training']) {
        $training_markers[] = $marker;
      }
    }

    $markers = $training_markers;
  }

  $data['h_markers'] = $markers;
  echo json_encode($data);

  wp_die();
}

add_action('wp_ajax_nopriv_getHubMarkers', 'return_hub_markers');
add_action('wp_ajax_getHubMarkers', 'return_hub_markers');

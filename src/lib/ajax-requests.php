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
      
      $key ++;
    }
  }

  return $data;
}


function return_map_markers() {
  //initiatives
  $i_filepath = TEMPLATEPATH . '/cache/i-cache.txt';
  $h_filepath = TEMPLATEPATH . '/cache/h-cache.txt';
  $cache_expiry = 3600;

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

  echo json_encode($data);
  wp_die();
}

add_action('wp_ajax_nopriv_getMapMarkers', 'return_map_markers');
add_action('wp_ajax_getMapMarkers', 'return_map_markers');

function return_healthcheck_graph_data() {
  if (!isset($_POST['value'])) {
    $results['success'] = false;
    echo json_encode($_POST);
    die();
  }

  $args = array(
    'post_type' => 'healthchecks',
    'posts_per_page' => -1,
    'orderby' => 'ID',
    'order' => 'DESC',
    'meta_query' => array(
      array(
        'key' => 'incomplete',
        'compare' => 'NOT EXISTS'
      )
    )
  );

  $posts = get_posts($args);
  
  $response_id = 0;
  
  //build responses array to contain all response data

  foreach($posts as $post) {
    // get associate initiave post object
    $initiative = get_post($post->post_title);
    
    if($initiative->post_status == 'publish') {
      $fields = get_fields($post->ID);
      if($fields) {
        foreach($fields as $group_key => $group) {
          foreach($group as $answer_key => $answer) {
            $answer_key = str_replace('group_', 'g', $group_key) . '_' . $answer_key;
            $responses[$response_id][$answer_key] = $answer;
          }
        }
        $response_id ++;
      }
    }
  }

  $output = array();
  
  //build array of answers indexed by question_key
  foreach($responses as $response) {
    foreach($response as $key => $item) {
      $answers[$key][] = $item;
    }
  }

  //average an build output from answers array
  foreach($answers as $key => $response_list) {
    $output_questions[] = $key;
    $response_total = 0;

    //remove empty responses
    $response_list = array_filter($response_list);

    foreach($response_list as $response) {
      $response_total += (int)$response;
    }
    
    if($response_total) {
      $output_responses[] = $response_total / count($response_list);  
    }

    $output_counts[] = count($response_list);
  }

  $data['count'] = max($output_counts);
  $data['questions'] = $output_questions;
  $data['averages'] = $output_responses;
  
  echo json_encode($data);
  wp_die();
}

add_action('wp_ajax_nopriv_getHealthcheckData', 'return_healthcheck_graph_data');
add_action('wp_ajax_getHealthcheckData', 'return_healthcheck_graph_data');

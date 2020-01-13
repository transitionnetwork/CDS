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
        $i_markers[$key]['permalink'] = parse_post_link($post->ID);
        $i_markers[$key]['title'] = get_the_title($post->ID);
        // $markers[$key]['excerpt'] = get_the_excerpt($post->ID);
        $key++;
      }
    }
	// Put the results in a transient. Expire after 12 hours.
  set_transient($hash, $i_markers, 12 * HOUR_IN_SECONDS );
  }

  //hubs
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

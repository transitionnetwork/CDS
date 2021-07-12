<?php
function ajax_get_intiative_markers($params) {
  if($params['type'] === "1" || $params['type'] === "2") {
    $args = array(
      'post_type' => 'initiatives',
      'posts_per_page' => -1,
    );
    
    if(array_key_exists('hub_name', $params)) {
      $args['tax_query'] = array(
        array (
          'taxonomy' => 'hub',
          'field' => 'slug',
          'terms' => $params['hub_name']
        )
      );
    } else if(array_key_exists('country', $params)) {
      $args['tax_query'] =  array(
        array (
          'taxonomy' => 'country',
          'field' => 'slug',
          'terms' => $params['country']
        )
      );
    }
  
    if(!empty($_POST['value']['search'])) {
      $args['s'] = $_POST['value']['search'];
    }

    $initiatives = get_posts($args);

    if($initiatives) {
      $results = array();
      foreach($initiatives as $initiative) {
        
        $map = get_field('map', $initiative->ID);
        
        $results[$initiative->ID]['type'] = 'initative';
        $results[$initiative->ID]['lat'] = $map['lat'];
        $results[$initiative->ID]['lng'] = $map['lng'];
        $results[$initiative->ID]['permalink'] = parse_post_link($initiative->ID);
        $results[$initiative->ID]['title'] = get_the_title($initiative->ID);
        $results[$initiative->ID]['age'] = get_initiatve_age($initiative->ID);

      }
      
      return $results;
    }
  }

  return array();
}

function ajax_get_hub_markers($params) {
  //hub queries second
  if($params['type'] === "1" || $params['type'] === "3") {
    $args = array(
      'hide_empty' => false
    );

    $hubs = get_terms('hub', $args);

    if($hubs) {
      $results = array();

      foreach($hubs as $hub) {
        if(
          !array_key_exists('training', $params) ||
          (array_key_exists('training', $params) && get_field('training', $hub) === 1)
        ) {

          ///ADD FILTERING BY hub_name and country (if)
        
          $map = get_field('map', $hub);
          
          $results[$hub->term_id]['type'] = 'hub';
          $results[$hub->term_id]['lat'] = $map['lat'];
          $results[$hub->term_id]['lng'] = $map['lng'];
          $results[$hub->term_id]['permalink'] = get_term_link($hub);
          $results[$hub->term_id]['title'] = $hub->name;
          $results[$hub->term_id]['training'] = get_field('training', $hub);
        }
      }

      return $results;
    }
  }

  return array();
} 

function ajax_get_map_markers() {
  if($_POST === 'getMarkers') {
    return false;
  }

  $params = array_key_exists('params', $_POST['value']) ? $_POST['value']['params'] : array();
  
  
  if(!array_key_exists('type', $params)) {
    // ALL/BOTH = 1
    // INITIATIVES = 2
    // HUBS = 3
    
    $params['type'] = "1";
  }

  $markers = array();
  $markers = array_merge(ajax_get_intiative_markers($params), ajax_get_hub_markers($params));
  
  echo json_encode(array($params, $markers));
  wp_die();
}

add_action('wp_ajax_nopriv_getMarkers', 'ajax_get_map_markers');
add_action('wp_ajax_getMarkers', 'ajax_get_map_markers');

<?php
function return_map_markers() {
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
  $hash = md5($query_string);

  $posts = get_posts($args);

  foreach($posts as $key => $post) {
    $map = get_field('map', $post->ID, false);
    $markers[$key]['center_lat'] = $map['center_lat'];
    $markers[$key]['center_lng'] = $map['center_lng'];
    $markers[$key]['permalink'] = get_the_permalink($post->ID);
    $markers[$key]['title'] = get_the_title($post->ID);
    $markers[$key]['excerpt'] = get_the_excerpt($post->ID);
  }

  echo json_encode($markers);
  wp_die();
}

add_action('wp_ajax_nopriv_getMapMarkers', 'return_map_markers');

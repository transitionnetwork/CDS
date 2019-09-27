<?php

function custom_initiative_endpoint() {
  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1
  );

  $posts = get_posts($args);
  $data = [];

  foreach($posts as $post) {
    $map = get_field('map', $post);
    
    $data[] = array(
      'title' => $post->post_title,
      'url' => get_the_permalink($post),
      'hub' => get_the_terms($post, 'hub')[0]->name,
      'location' => array(
        'lat' => $map['markers'][0]['lat'],
        'lng' => $map['markers'][0]['lng'],
        'label' => $map['markers'][0]['label'],
        'country' => get_the_terms($post, 'country')[0]->name
      ),
    );
  }

  if(!empty($data)) {
    return $data;
  }

  return;
}

function add_custom_initiatives_api() {
  register_rest_route( 'cds/v1', '/initiatives', array(
    'methods' => 'GET',
    'callback' => 'custom_initiative_endpoint',
  ));
}
add_action( 'rest_api_init', 'add_custom_initiatives_api');

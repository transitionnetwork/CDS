<?php

function custom_initiative_endpoint() {
  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1
  );

  $posts = get_posts($args);
  $data = [];
  $topic_output = '';
  $hub_output = '';

  foreach($posts as $post) {
    $topics = get_the_terms($post, 'topic');
    if($topics) {
      $topic_list = array();
      foreach($topics as $topic) {
        $topic_list[] = $topic->name;
      }
      
      $topic_output = implode(' | ', $topic_list);
    }

    $hubs = get_the_terms($post, 'hub');
    if($hubs) {
      $hub_list = array();
      foreach($hubs as $hub) {
        $hub_list[] = $hub->name;
      }
      
      $hub_output = implode(' | ', $hub_list);
    }
    
    
    $map = get_field('map', $post);
    
    $data[] = array(
      'title' => $post->post_title,
      'url' => get_the_permalink($post),
      'logo' => get_field('logo', $post)['url'],
      
      'hubs' => $hub_output,
      'topics' => $topic_output,

      'description' => apply_filters('the_content', get_post_field('post_content', $post->ID)),
      
      'location' => array(
        'address' => get_field('address_line_1', $post),
        'city' => get_field('city', $post),
        'province' => get_field('province', $post),
        'postal_codes' => get_field('postal_codes', $post),
        'country' => get_the_terms($post, 'country')[0]->name,
        'lat' => $map['markers'][0]['lat'],
        'lng' => $map['markers'][0]['lng'],
        'label' => $map['markers'][0]['label']
      ),

      'contact' => array(
        // 'email' => get_field('email', $post),
        'website' => get_field('website', $post),
        'twitter' => get_field('twitter', $post),
        'facebook' => get_field('facebook', $post),
        'instagram' => get_field('instagram', $post),
        'youtube' => get_field('youtube', $post),
        'additional_web_addresses' => get_field('additional_web_addresses', $post)
      )
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

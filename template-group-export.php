<?php
/**
 * Template Name: Export - Group List
 */


$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'orderby' => 'title',
  'order' => 'ASC'
);

$posts = get_posts($args);

$column_titles = array(
  'ID',
  'Name',
  'Hub',
  'Topics',
  'Country',
  'Location',
  'Description',
  'Email',
  'Website URL',
  'Twitter URL',
  'Facebook URL',
  'Instagram URL',
  'Youtube URL'
);

$export_data = array();
$export_data[] = $column_titles;

foreach($posts as $key => $post) {
  setup_postdata($post);
  
  $hubs = wp_get_post_terms($post->ID, 'hub');
  if($hubs) {
    $hub = $hubs[0]->name;
  } else {
    $hub = null;
  }

  $countries = wp_get_post_terms($post->ID, 'country');
  if($countries) {
    $country = $countries[0]->name;
  } else {
    $country = null;
  }

  $topics = get_the_terms($post, 'topic');
  if($topics) {
    $topic_names = [];
    foreach ($topics as $topic) {
      $topic_names[] = $topic->name;
    }
    $topic_names = implode(', ', $topic_names);
  } else {
    $topic_names = null;
  }

  //Location
  $location = null;
  if (get_field('address_line_1')) {
    $location = array();

    if(get_field('address_line_1')) {
      $location[] = get_field('address_line_1');
    }

    if(get_field('city')) {
      $location[] = get_field('city');
    }

    if(get_field('province')) {
      $location[] = get_field('province');
    }

    if(get_field('postal_code')) {
      $location[] = get_field('postal_code');
    }

    $location = implode(', ', $location);
  } else if (get_field('map') && get_field('map')['markers']) {
    $location = get_field('map')['markers'][0]['default_label'];
  }

  $export_data[] = array(
    get_the_ID(),
    get_the_title(),
    $hub,
    $topic_names,
    $country,
    trim($location),
    get_the_content(),
    get_field('email'),
    get_field('website'),
    get_field('twitter'),
    get_field('facebook'),
    get_field('instagram'),
    get_field('youtube'),
  );

  wp_reset_postdata();
}

//secure the export
if(is_user_role('administrator') || is_user_role('super_hub')) {
  outputCsv(date('Ymd') . '_transition_groups_export.csv', $export_data);
} else {
  wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
} ?>

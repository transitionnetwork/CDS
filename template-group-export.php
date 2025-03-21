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
  'Tags',
  'Country',
  'Location',
  'Description',
  'Public Email',
  'Author Email',
  'Co-Author Emails',
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


  $tags = get_group_tags($post); 
  $tag_output = '';
  
  if($tags) {
    foreach($tags as $tag) {
      $tag_output[] = $tag['label'];
    }
    $tag_output = implode(', ', $tag_output);
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

  $co_authors = ma_get_co_authors($post->ID);
  $co_author_emails = array();
  foreach($co_authors as $co_author_id) {
    $co_author_emails[] = get_userdata($co_author_id)->user_email;
  }
  $co_author_emails = implode(', ', $co_author_emails);

  $export_data[] = array(
    get_the_ID(),
    get_the_title(),
    $hub,
    $tag_output,
    $country,
    trim($location),
    strip_tags(get_field('description', $post), '<p><em><strong>'),
    get_field('email'),
    get_the_author_meta('user_email'),
    $co_author_emails,
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

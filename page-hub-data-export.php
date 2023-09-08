<?php

$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'post_status' => 'publish',
  'tax_query' => array(
    array(
      'taxonomy' => 'hub',
      'terms' => get_query_var('hub_id')
    ),
  )
);

$init_query = new WP_Query($args); ?>

<?php session_start();
$export_data[] = ["Name", "Status", "Hub", "Country", "Last Healthcheck Date", "Topics", "Private Email", "Email Address", "Website URL", "Twitter", "Facebook", "Instagram", "Youtube"];

while ($init_query->have_posts()) : $init_query->the_post();

  $country_term = get_the_terms($post->ID, 'country')[0];
  $hub_term = get_the_terms($post->ID, 'hub')[0];

  $name = get_the_title();
  $status = get_post_status();
  $hub = $hub_term->name;
  $country = $country_term->name;

  $healthcheck_args = array(
    'post_type' => 'healthchecks',
    'title' => $post->ID,
    'posts_per_page' => 1,
    'orderby' => 'post_date',
    'order' => 'DESC'
  );

  $latest_healthcheck = '';
  
  $latest_healthcheck_post = get_posts($healthcheck_args);

  if($latest_healthcheck_post) {
    $latest_healthcheck = $latest_healthcheck_post[0]->post_date;
  }

  $topics = get_the_terms($post, 'topic');
  $topic_output = '';
  
  if($topics) {
    $topic_list = array();
    foreach($topics as $topic) {
      $topic_list[] = $topic->name;
    }

    $topic_output = implode(', ', $topic_list);
  }

  $email = get_field('email', $post->ID);

  $address = get_field('address_line_1', $post->ID);
  $city = get_field('city', $post->ID);
  $province = get_field('province', $post->ID);
  $postal_code = get_field('postal_code', $post->ID);

  $website = get_field('website', $post->ID);
  $twitter = get_field('twitter', $post->ID);
  $facebook = get_field('facebook', $post->ID);
  $instagram = get_field('instagram', $post->ID);
  $youtube = get_field('youtube', $post->ID);

  $export_data[] = [$name, $status, $hub, $country, $latest_healthcheck, $topic_output, $email, $address, $city, $province, $postal_code, $website, $twitter, $facebook, $instagram, $youtube];


endwhile; ?>
<?php outputCsv(date('Ymd') . '_' . $hub_term->slug . '_Initiative_Export.csv', $export_data); ?>

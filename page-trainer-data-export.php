<?php

$args = array(
  'post_type' => 'trainers',
  'posts_per_page' => -1,
  'post_status' => 'publish'
);

$init_query = new WP_Query($args); ?>

<?php session_start();
$export_data[] = ["Name", "Bio", "Profile Photo", "Location", "Website", "Languages", "Topics", "Countries"];

while ($init_query->have_posts()) : $init_query->the_post();

  $languages = get_list_terms('trainer_language');
  $topics = get_list_terms('trainer_topic');
  $countries = get_list_terms('country');

  $name = get_the_title();
  $bio = get_field('general_information_trainer_bio');

  $training_photo = get_field('general_information_trainer_photo');
  $photo = ($training_photo) ? $training_photo['url'] : null;

  $website = get_field('general_information_your_website');

  $map = (!empty(get_field('general_information_location'))) ? get_field('general_information_location')['map'] : null;
  $location = ($map && $map['markers']) ? $map['markers'][0]['label'] : null;

  $export_data[] = [$name, $bio, $photo, $location, $website, $languages, $topics, $countries];


endwhile; ?>
<?php outputCsv(date('Ymd') . 'Transition_Groups_Trainers_Export.csv', $export_data); ?>

<?php
 $field_data = array(
  'field_618e62c4b7f7a' => 'general_information_name',
  'field_618e62ceb7f7b' => 'general_information_email',
  'field_618e636fb7f7e' => 'general_information_location',
  'field_618e63e0b7f80' => 'general_information_your_website',
  'field_618e6433b7f81' => 'general_information_trainer_confirmation',
  'field_618e6471b7f82' => 'general_information_trainer_bio',
  'field_618e6489b7f83' => 'general_information_trainer_photo',
  'field_618e64b3b7f85' => 'criteria_trainer_course',
  'field_618e64ffb7f86' => 'criteria_organisation_accreditation',
  'field_61a64b8eccc18' => 'criteria_course_details',
  'field_618e6576f2579' => 'criteria_led_course',
  'field_618e72997cf8e' => 'criteria_led_course_detail',
  'field_618e6597f257a' => 'criteria_involvement',
  'field_618e6611f257b' => 'criteria_involvement_intention',
  'field_618e743468c78' => 'criteria_involvement_details',
  'field_618e6654f257c' => 'criteria_development_path',
  'field_618e66961ed95' => 'additional_criteria_q1',
  'field_618e66d01ed96' => 'additional_criteria_q2',
  'field_618e66d81ed97' => 'additional_criteria_q3',
  'field_618e66e51ed98' => 'additional_criteria_q4',
  'field_618e66f81ed99' => 'additional_criteria_q5',
  'field_618e67051ed9a' => 'additional_criteria_q6',
  'field_618e67141ed9b' => 'additional_criteria_q7',
  'field_618e6766a128f' => 'additional_information_trainer_topic',
  'field_61a6484aff5f2' => 'additional_information_topic_additional_details',
  'field_618e6790a1290' => 'additional_information_trainer_languages',
  'field_618e67e4a1292' => 'additional_information_trainer_countries',
  'field_619f669b5cda7' => 'additional_information_trainer_regions',
  'field_618e6819a1294' => 'community_of_practice_community',
  'field_618e682aa1295' => 'community_of_practice_aims',
  'field_618e6859a1296' => 'community_of_practice_share'
);

$labels = [];
foreach($field_data as $key => $name) {
  $field_object = get_field_object($key);
  $labels[] = $field_object['label'];
  $types[] = $field_object['type'];
}

$args = array(
  'post_type' => 'trainers',
  'posts_per_page' => -1,
  'post_status' => 'publish'
);

if(is_user_trainer_admin()) {
  $args['post_status'] = array('pending', 'publish');
}

$init_query = new WP_Query($args);
?>

<?php session_start();
$export_data[] = $labels;

while ($init_query->have_posts()) : $init_query->the_post();
  $export_row = [];
  foreach($field_data as $key => $name) {
    $field_object = get_field_object($key);
    $field = get_field($name);

    if($field_object['type'] === 'image') {
      $export_row[] = ($field) ? $field['url'] : null;
    } elseif ($field_object['type'] === 'taxonomy') {
      $export_row[] = get_list_terms($field_object['name']);
    } elseif ($field_object['type'] === 'clone' && is_array($field) && array_key_exists('map', $field)) {
      $export_row[] = ($field && $field['map']['markers']) ? $field['map']['markers'][0]['label'] : null;
    } else {
      $export_row[] = $field;
    }
  }

  $export_data[] = $export_row;
endwhile; ?>

<?php
if(is_user_logged_in() && is_user_role(array('super_hub', 'administrator'))) {
  outputCsv(date('Ymd') . 'Transition_Groups_Trainers_Export.csv', $export_data);
} ?>

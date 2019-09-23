<?php
/**
 * Template Name: Export - Healthcheck
 */

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

$answers = array();
$healthcheck_ids = array();

$i = 0;
foreach($posts as $post) {
  // only display the first ID;
  if(!in_array($post->post_title, $healthcheck_ids)) {
    $healthcheck_ids[] = $post->post_title;
    $question_groups = get_fields($post->ID);
    
    $initiative_title = get_post($post->post_title);
    
    if($question_groups) {
      $questions[0] = 'Initiative';
      $answers[$i][0] = $initiative_title->post_title;
      foreach($question_groups as $key => $group) {
        foreach ($group as $label => $response) {
          // add question the first time
          if($i == 0) {
            $full_question = get_field_object($key . '_' . $label)['label'];
            $questions[] = $full_question;
          }
          $answers[$i][] = $response;
        }
      }
    }
  }
  $i++;
}

$export_data[] = $questions;
foreach($answers as $answer_set) {
  $export_data[] = $answer_set;
}

// var_dump($export_data);
outputCsv(date('Ymd') . '_healthcheck_responses.csv', $export_data); ?>

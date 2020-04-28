<?php
function return_healthcheck_graph_data() {
  if (!isset($_POST['value'])) {
    $results['success'] = false;
    echo json_encode($_POST);
    die();
  }

  //build array of question ids and questions
  $acf_fields = acf_get_fields( 2068 );
  $question_index = array();
  
  if($acf_fields) {
    foreach($acf_fields as $field) {
      if(array_key_exists('sub_fields', $field)) {
        foreach($field['sub_fields'] as $sub_field) {
          $question_index[str_replace('group_', 'g', $field['name']) . '_' . $sub_field['name']] = $sub_field['label'];
        }
      }
    }
  }

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
  
  $response_id = 0;
  
  //build responses array to contain all response data
  $field_names[] = array();

  foreach($posts as $post) {
    // get associate initiave post object
    $initiative = get_post($post->post_title);
    
    if($initiative->post_status == 'publish') {
      $fields = get_fields($post->ID);
      if($fields) {
        foreach($fields as $group_key => $group) {
          foreach($group as $answer_key => $answer) {
            $field_names[] = $group_key . '_' . $answer_key;
            $answer_key = str_replace('group_', 'g', $group_key) . '_' . $answer_key;
            $responses[$response_id][$answer_key] = $answer;
          }
        }
        $response_id ++;
      }
    }
  }

  $field_names = array_unique($field_names);

  $output = array();
  
  //build array of answers indexed by question_key
  foreach($responses as $response) {
    foreach($response as $key => $item) {
      $answers[$key][] = $item;
    }
  }

  //average an build output from answers array
  foreach($answers as $key => $response_list) {
    $output_questions[] = '<a>' . $key . '</a><span>{' . $question_index[$key] . '}</span>';
    $response_total = 0;

    //remove empty responses
    $response_list = array_filter($response_list);

    foreach($response_list as $response) {
      $response_total += (int)$response;
    }
    
    if($response_total) {
      $output_responses[] = $response_total / count($response_list);  
    }

    $output_counts[] = count($response_list);
  }

  $data['count'] = max($output_counts);
  $data['questions'] = $output_questions;
  $data['averages'] = $output_responses;
  
  echo json_encode($data);
  wp_die();
}

add_action('wp_ajax_nopriv_getHealthcheckData', 'return_healthcheck_graph_data');
add_action('wp_ajax_getHealthcheckData', 'return_healthcheck_graph_data');

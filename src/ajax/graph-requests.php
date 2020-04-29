<?php
function get_question_index() {
    //build array of question ids and questions
  $acf_fields = acf_get_fields( 2068 );
  $question_index = array();
  
  if($acf_fields) {
    foreach($acf_fields as $field) {
      if(array_key_exists('sub_fields', $field)) {
        foreach($field['sub_fields'] as $sub_field) {
          $question_index[str_replace('group_', 'g', $field['name']) . '_' . $sub_field['name']] = array(
            'label' => $sub_field['label'],
            'field_key' => $sub_field['key']
          );
        }
      }
    }
  }

  return $question_index;
}

function return_healthcheck_graph_data() {
  if (!isset($_POST['value'])) {
    $results['success'] = false;
    echo json_encode($_POST);
    die();
  }

  $question_index = get_question_index();

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
    $output_questions[] = '<a>' . $key . '</a><span>{' . $question_index[$key]['label'] . '}</span>';
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


function return_single_healtcheck_data() {
  if (!isset($_POST['value'])) {
    $results['success'] = false;
    echo json_encode($_POST);
    die();
  }
  
  $alphas = range('A', 'Z');
  
  $fields = get_field_objects($_POST['value']['post_id']);
  $questions = array();
  $data['fields'] = $fields;

  if($fields) {
    foreach ($fields as $field) {
      if($field['type'] == 'group') {
        $sub_fields = $field['sub_fields'];
        foreach ($sub_fields as $key => $sub_field) {
          $group_letter = $alphas[str_replace('group_', '', $field['name']) - 1];
          $questions[$sub_field['key']] = array(
            'label' => $sub_field['label'],
            'name' => str_replace('group_', 'g', $field['name']) . '_' . $sub_field['name'],
            'response' => get_field($field['name'] . '_' . $sub_field['name'], $_POST['value']['post_id']),
            'identifier' => $group_letter . ($key + 1)
          );
        }
      }
    }
  }

  $output_data = [];
  foreach($questions as $question) {
    $output_data[] = $question['response'];
  }
  
  $output_questions = [];
  foreach($questions as $question) {
    $output_questions[] = '<a>' . $question['identifier'] . '</a><span>{' . $question['label'] . '}</a>';
  }
  
  $data['data'] = $output_data;
  $data['questions'] = $output_questions;
  echo json_encode($data);
  wp_die();
}
add_action('wp_ajax_nopriv_getSingleHealthcheckData', 'return_single_healtcheck_data');
add_action('wp_ajax_getSingleHealthcheckData', 'return_single_healtcheck_data');

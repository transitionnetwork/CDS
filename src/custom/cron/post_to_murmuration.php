<?php

function post_groups_to_murmaration() {
  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1,
    'fields' => 'ids'
  );

  $post_ids = get_posts($args);
  if($post_ids) {
    foreach($post_ids as $post_id) {
      $body = array(
        'profile_url' => 'https://transitiongroups.org/wp-json/cds/v1/get-groups-murmurations/' . $post_id
      );

      $response = wp_remote_post('https://index.murmurations.network/v2/nodes-sync', array(
        'method' => 'POST',
        'body' => json_encode($body),
        'headers' => [
		      'Content-Type' => 'application/json',
	      ],
      ))['body'];

      $response = json_decode($response);
      
      if($response->errors) {
        $errors = $response->errors;
        foreach($errors as $error) {
          var_dump($error);
        }
      } else {
        $node_id = $response->data->node_id;
        $status = $response->data->status;
        var_dump($response);
      }

      die();
    }
  }
}

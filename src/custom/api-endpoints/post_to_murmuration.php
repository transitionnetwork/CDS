<?php
function post_to_murmuration_api($post) {
  $body = array(
    'profile_url' => home_url('wp-json/cds/v1/get-groups-murmurations/') . $post->ID
  );

  $response = wp_remote_post('https://index.murmurations.network/v2/nodes-sync', array(
    'method' => 'POST',
    'body' => json_encode($body),
    'headers' => [
      'Content-Type' => 'text/plain',
    ],
  ));
  
  if($response->errors) {
    $errors = $response->errors;
    update_post_meta($post->ID, 'murmurations_error', json_encode($error));
    
    add_log_message('ERROR post-' . $post->ID . ' ' . $error);
  } else {
    $body =  json_decode( wp_remote_retrieve_body( $response ), true );
    $node_id = $body->data->node_id;
    $status = $body->data->status;
  
    update_post_meta($post->ID, 'murmurations_node_id', $node_id);
    update_post_meta($post->ID, 'murmurations_status', $status);
    update_post_meta($post->ID, 'murmurations_error', null);

    add_log_message('UPDATE post-' . $post->ID . ' ' . $body);
  }

  return;
}

function remove_from_murmuration_api($post) {
  $node_id = get_post_meta( $post->ID, 'murmurations_node_id', true );

  // var_dump($node_id);
  
  if($node_id) {
    $response = wp_remote_post('https://index.murmurations.network/v2/nodes/' . $node_id, array(
    'method' => 'DELETE',
    'headers' => [
      'Content-Type' => 'text/plain',
    ]))['body'];
  }

  add_log_message('DELETE post-' . $post->ID . ' ' . $response);

  if($response->errors) {
    $errors = $response->errors;
    update_post_meta($post->ID, 'murmurations_error', json_encode($error));
  } else {
    update_post_meta($post->ID, 'murmurations_status', $status);
    update_post_meta($post->ID, 'murmurations_error', null);
  }
}

function send_murmurations_request($post_id, $post, $update) {
  if(get_environment() === 'production') {
    if($post->post_status === 'publish') {
      post_to_murmuration_api($post);
    } else {
      remove_from_murmuration_api($post);
    }
  }
}

add_action( 'save_post_initiatives', 'send_murmurations_request', 10, 3 );

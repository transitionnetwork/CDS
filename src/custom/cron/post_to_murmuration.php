<?php
function post_to_murmuration_api($post) {
  return;
  ////////
  $body = array(
    'profile_url' => 'https://transitiongroups.org/wp-json/cds/v1/get-groups-murmurations/' . $post->ID
  );

  $response = wp_remote_post('https://test-index.murmurations.network/v2/nodes-sync', array(
    'method' => 'POST',
    'body' => json_encode($body),
    'headers' => [
      'Content-Type' => 'application/json',
    ],
  ))['body'];

  $response = json_decode($response);

  // SOME SORT OF LOGGING NEEDS TO HAPPEN HERE

  return;
}

function remove_from_murmuration_api($post) {
  return;
}

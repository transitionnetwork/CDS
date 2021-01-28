<?php

$user = get_field('mailjet_api_username', 'options');
$key = get_field('mailjet_api_key', 'options');

if($user && $key) {
  $request = wp_remote_get(
    'https://api.mailjet.com/v3/REST/bouncestatistics',
    array(
      'timeout' => 100,
      'headers' => array(
        'Authorization' => 'Basic ' . base64_encode($user . ':' . $key)
      )
    )
  );
  
  var_dump($request);
  
  $body = wp_remote_retrieve_body($request);
  file_put_contents(TEMPLATEPATH . '/info/bounces.json', $body);
  var_dump('saved to info/bounces')
}

?>

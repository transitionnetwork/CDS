<?php
function add_webhook_endpoints() {
  register_rest_route( 'cds/v1', '/mailgun', array(
    'methods' => 'POST',
    'callback' => 'process_mailgun_endpoint',
  ));
}
add_action( 'rest_api_init', 'add_webhook_endpoints');

function process_mailgun_endpoint(WP_REST_Request $request) {
  $attributes = $request->get_attributes();
  $json = $request->get_json_params();
  $body = $request->get_body();
  var_dump($request);
  var_dump($attributes);
  var_dump($json);
  var_dump($body);
}

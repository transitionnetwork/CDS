<?php
function add_webhook_endpoints() {
  register_rest_route( 'cds/v1', '/mailgun', array(
    'methods' => 'POST',
    'callback' => 'process_mailgun_endpoint',
  ));
}
add_action( 'rest_api_init', 'add_webhook_endpoints');

function process_mailgun_endpoint(WP_REST_Request $request) {
  $body = $request->get_body();
  $body = json_decode($body, TRUE);

  $query_params = $request->get_query_params();
  $event_type = array_key_first($query_params);
  
  $post_id = (int)$body['event-data']['user-variables']['post_id'];
  $recipient = $body['event-data']['recipient'];

  if($post_id) {
    //set mail_event field to delivered or failure for query sakes
    if(in_array($event_type, array('delivered', 'failure', 'opens', 'clicks'))) {
      update_post_meta( $post_id, 'last_mail_date', date('Y-m-d H:i:s'));
      update_post_meta( $post_id, 'last_mail_event', $event_type);
    }
  
    $mail_log = get_post_meta($post_id, 'mail_log', true);
    if(!is_array($mail_log)) {
      $mail_log = array();
    }
    
    $mail_log[] = date('Y-m-d H:i:s') . ' - ' . $recipient . ' - ' . $event_type;
    update_post_meta( $post_id, 'mail_log', $mail_log);
  }

  return;
}

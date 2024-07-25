<?php
function mailchimp_add_author_to_list($post) {
  $hub_mailchimp_audience_map = array(
    800 => 'edd02f822a', // London and SE
    284 => '636863f48c' // England and Wales
  );

  $hub_id = get_the_terms($post, 'hub')[0]->term_id;

  if(!array_key_exists($hub_id, $hub_mailchimp_audience_map)) {
    //check that audience exists
    return;
  }

  $author = get_userdata( $post->post_author);

  require_once TEMPLATEPATH . '/vendor/autoload.php';
  
  $api_key = get_field('mailchimp_api_key', 'options');
  $region = get_field('mailchimp_region', 'options');
  
  $client = new MailchimpMarketing\ApiClient();
  $client->setConfig([
      'apiKey' => $api_key,
      'server' => $region,
  ]);
  
  $response = $client->lists->addListMember($hub_mailchimp_audience_map[$hub_id], [
      "email_address" => $author->user_email,
      "status" => "subscribed",
      "tags" => array("TGroups-org--auto-sync"),
      "merge_fields" => array(
        'MMERGE3' => get_the_title($post)
      )
  ]);
}

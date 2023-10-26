<?php
function mailchimp_add_author_to_list($post) {
  $hub_mailchimp_audience_map = array(
    800 => 'edd02f822a', // London and SE
    284 => '636863f48c' // England and Wales
  );

  $author = get_userdata( $post->post_author);

  require_once TEMPLATEPATH . '/vendor/autoload.php';
  
  $client = new MailchimpMarketing\ApiClient();
  $client->setConfig([
      'apiKey' => 'f05fe9940c9efe4a1828104b45410ed5-us16',
      'server' => 'us16',
  ]);
  
  $response = $client->lists->addListMember($hub_mailchimp_audience_map[800], [
      "email_address" => $author->user_email,
      "status" => "subscribed",
  ]);
}

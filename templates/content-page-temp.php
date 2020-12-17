<?php
die();

$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1
);

$posts = get_posts($args);
if($posts) {
  $author_emails = array();
  foreach($posts as $post) {
    $author_id = get_post_field( 'post_author', $post->ID );
    $userdata = get_userdata($author_id);
    $author_emails[] = $userdata->user_email;
  }

  var_dump(count($author_emails));
  $author_emails = array_unique($author_emails);
  var_dump(count($author_emails));
}


die();
$request = wp_remote_get(
  'https://api.sendgrid.com/v3/suppression/bounces?start_time=1606780800',
  array(
    'timeout' => 100,
    'headers' => array(
      'Authorization' => 'Bearer #'
    )
  )
);

$body = wp_remote_retrieve_body($request);
file_put_contents(TEMPLATEPATH . '/bounces.json', $body);

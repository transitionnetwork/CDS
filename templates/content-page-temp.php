<?php
die();
  $user_id = 1;
  if(get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY)) {
    email_autologin_reminder_email($user_id);
    var_dump('success');
  }
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
    $author_ids[] = $author_id;
  }

  var_dump($author_ids);

  if($author_ids) {
    foreach($author_ids as $user_id) {
      $cleanedKey = pkg_autologin_generate_code();
  
      if (!add_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey, True)) {
        if (!update_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey)) {
          // Check if the key was changed at all - if not this is an error of update_user_meta
          if (get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, True) != $cleanedKey) {
            wp_die(__('Failed to update autologin link.', PKG_AUTOLOGIN_LANGUAGE_DOMAIN));
          }
        }
      }
    }
  }

  echo implode('|', $author_ids);

  // var_dump(count($author_emails));
  // $author_emails = array_unique($author_emails);
  // var_dump(count($author_emails));
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

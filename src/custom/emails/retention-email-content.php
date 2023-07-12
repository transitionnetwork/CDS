<?php
function custom_email_autologin_reminder_email($post_id) {
  $post = get_post($post_id);
  
  $user_id =  get_the_author_meta('ID', $post->post_author);
  
  $to = array(
    get_the_author_meta('user_email', $post->post_author)
  );

  $headers = 'X-Mailgun-Variables: ' .$post_id;

  $email_post_id = 9182;
    
  $subject = get_field('subject', $email_post_id);
  
  $link = 'https://' . $_SERVER['SERVER_NAME'] . '/account/?autologin_code=' . get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, true) . '#nav-initiative-admin';

  $page = get_post($email_post_id);
  $body = apply_filters('the_content', $page->post_content);
  $body = str_replace('#login_link#', $link, $body);
 
  var_dump($link);
  var_dump($to);
  var_dump($headers);
  var_dump($subject);
  echo $body;
  die();
  wp_mail( $to, $subject, $body, $headers);
}

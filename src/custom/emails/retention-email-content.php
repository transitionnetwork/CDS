<?php
function custom_email_autologin_reminder_email($post_id) {
  
  $post = get_post($post_id);
  $user_id =  get_the_author_meta('ID', $post->post_author);

  $cleanedKey = pkg_autologin_generate_code();

  if (!add_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey, true) && !get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY)) {
    if (!update_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey)) {
      // Check if the key was changed at all - if not this is an error of update_user_meta
      if (get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, true) != $cleanedKey) {
        wp_die(__('Failed to update autologin link.', PKG_AUTOLOGIN_LANGUAGE_DOMAIN));
      }
    }
  } else {
    //add a created datetime for the key
    add_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY . '_created', date('Y-m-d H:i:s'), true);
  }
  
  $link = 'https://' . $_SERVER['SERVER_NAME'] . '/account/?autologin_code=' . get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, true) . '#nav-initiative-admin';
  
  $to = array(
    get_the_author_meta('user_email', $post->post_author)
  );

  $headers = 'X-Mailgun-Variables: {"post_id" : ' . $post_id . '}';

  $hub = get_the_terms($post->ID, 'hub')[0];
  $hub_email_content = get_field('hub_emails_email_1', 'term_' . $hub->term_id);

  if($hub_email_content) {
    $subject = $hub_email_content['subject'];
    $body = $hub_email_content['content'];
  } else {
    $email_post_id = 9182;
  
    $subject = get_field('subject', $email_post_id);
    $page = get_post($email_post_id);
    $body = apply_filters('the_content', $page->post_content);
  }
  
  $subject = str_replace('#post_name#', get_the_title($post_id), $subject);
  $body = str_replace('#post_name#', get_the_title($post_id), $body);
  $body = str_replace('#login_link#', $link, $body);

  if (get_environment() === 'production') {
    //dont send cron mail in dev environments
    wp_mail( $to, $subject, $body, $headers);
  }
}

<?php
function custom_email_autologin_reminder_email($post_id) {
  
  $post = get_post($post_id);
  $user_id =  get_the_author_meta('ID', $post->post_author);

  $cleanedKey = pkg_autologin_generate_code();

  if (!add_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey, True) && !get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY)) {
    if (!update_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey)) {
      // Check if the key was changed at all - if not this is an error of update_user_meta
      if (get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, True) != $cleanedKey) {
        wp_die(__('Failed to update autologin link.', PKG_AUTOLOGIN_LANGUAGE_DOMAIN));
      }
    }
  }
  
  $link = 'https://' . $_SERVER['SERVER_NAME'] . '/account/?autologin_code=' . get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, true) . '#nav-initiative-admin';
  
  $to = array(
    get_the_author_meta('user_email', $post->post_author)
  );

  $headers = 'X-Mailgun-Variables: {"post_id" : ' . $post_id . '}';

  $email_post_id = 9182;
  $subject = get_field('subject', $email_post_id);


  $page = get_post($email_post_id);
  $body = apply_filters('the_content', $page->post_content);
  $body = str_replace('#login_link#', $link, $body);
 
  wp_mail( $to, $subject, $body, $headers);
}

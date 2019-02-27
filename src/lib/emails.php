<?php
function custom_wp_new_user_notification_email($wp_new_user_notification_email, $user, $blogname)
{
  $key = get_password_reset_key($user);
  $message = '<p>';
  $message .= sprintf(__('Welcome to Transition Initiative ,')) . '</p><p>';
  $message .= 'To set your password, visit the following address:' . '</p><p>';
  $message .= site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . '</p><p>';
  $message .= "Kind regards," . '</p><p>';
  $message .= "The Transition Network Team" . '</p><p>';
  $wp_new_user_notification_email['message'] = $message;
  
  return $wp_new_user_notification_email;
}
add_filter('wp_new_user_notification_email', 'custom_wp_new_user_notification_email', 10, 3);

function set_content_type($content_type)
{
  return 'text/html';
}
add_filter('wp_mail_content_type', 'set_content_type');

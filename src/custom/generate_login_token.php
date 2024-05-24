<?php
function generate_login_token_post_requests() {
  if(array_key_exists('generate_autologin_link', $_POST)) {
    $user_id = (int)$_POST['generate_autologin_link'];
    
    $cleanedKey = pkg_autologin_generate_code();

    if (!add_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey, True) && !get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY)) {
      if (!update_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey)) {
        // Check if the key was changed at all - if not this is an error of update_user_meta
        if (get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, True) != $cleanedKey) {
          wp_die(__('Failed to update autologin link.', PKG_AUTOLOGIN_LANGUAGE_DOMAIN));
        }
      }
    }

    wp_redirect(add_query_arg('added', 'login_token_generated', get_the_permalink()));
    exit;
  }
}

if (!is_admin()) {
  add_action('template_redirect', 'generate_login_token_post_requests');
}

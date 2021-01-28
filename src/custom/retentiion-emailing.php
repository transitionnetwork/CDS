<?php

function retention_emailing_get_authors() {
  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1,
    
  );
  
  $posts = get_posts($args);
  if($posts) {
    $author_ids = array();
    foreach($posts as $post) {
      $author_id = get_post_field( 'post_author', $post->ID );
      $userdata = get_userdata($author_id);
  
      $registered = strtotime($userdata->user_registered);
      $time_ago = date('Y-m-d H:i:s', strtotime('-6 months'));
      $time_ago = strtotime($time_ago);
  
      if($time_ago > $registered) {
        $author_ids[] = $author_id;
      }
    }
  
    $author_ids = array_values(array_unique($author_ids));
    update_option('retention_author_ids', $author_ids);
    update_option('retention_author_ids_saved', date('Y-m-d H:i:s'));
    // file_put_contents(TEMPLATEPATH . '/info_author_ids.json', json_encode($author_ids));
    var_dump('retention list saved with ' . count($author_ids) . ' authors');
  }

}

function retention_emailing_send_emails() {
  $author_ids = get_option('retention_author_ids');
  $start = get_field('email_start', 'options');
  $stop = get_field('email_stop', 'options');

  if($author_ids && isset($start) && isset($stop)) {
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

    foreach($author_ids as $k => $user_id) {
      if($k >= $start && $k <= $stop) {
        echo '<div>k: ' . $k . ' user_id:' . $user_id . '</div>'; 
        if(get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY) && !get_user_meta($user_id, 'inactive_login_reminder_email_sent') && get_environment() === 'production') {
          email_autologin_reminder_email($user_id);
          add_user_meta($user_id, 'inactive_login_reminder_email_sent', date('Y-m-d H:i:s'));
          var_dump($user_id . ': success');
        } else {
          echo '<div>';
          var_dump($user_id . ': no success');
          var_dump(get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY) );
          var_dump(get_user_meta($user_id, 'inactive_login_reminder_email_sent'));
          var_dump(get_environment());
          echo '</div>';
        }
      }
    }
  }
}

function retention_save_bounce_emails() {
  $user = get_field('mailjet_api_username', 'options');
  $key = get_field('mailjet_api_key', 'options');

  if($user && $key) {
    $request = wp_remote_get(
      'https://api.mailjet.com/v3/REST/bouncestatistics',
      array(
        'timeout' => 100,
        'headers' => array(
          'Authorization' => 'Basic ' . base64_encode($user . ':' . $key)
        )
      )
    );
    
    var_dump($request);
    
    $body = wp_remote_retrieve_body($request);
    file_put_contents(TEMPLATEPATH . '/info_bounces.json', $body);
    var_dump('bounce list saved');
  }
}

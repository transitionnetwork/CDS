<?php
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  
);

$posts = get_posts($args);
if($posts) {
  $author_ids = array();
  $author_emails = array();
  foreach($posts as $post) {
    $author_id = get_post_field( 'post_author', $post->ID );
    $userdata = get_userdata($author_id);

    $registered = strtotime($userdata->user_registered);
    $time_ago = date('Y-m-d H:i:s', strtotime('-6 months'));
    $time_ago = strtotime($time_ago);

    if($time_ago > $registered) {
      $author_emails[] = $userdata->user_email;
      $author_ids[] = $author_id;
    }
  }

  file_put_contents(TEMPLATEPATH . '/info/author_ids.json', json_encode($author_ids));
}


die();

// $author_ids = json_decode(file_get_contents(TEMPLATEPATH . '/info/author_ids.json'));
// $start = get_field('email_start', 'options');
// $finish = get_field('email_stop', 'options');

// if($author_ids && $start && $stop) {
//   foreach($author_ids as $user_id) {
//     $cleanedKey = pkg_autologin_generate_code();

//     if (!add_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey, True)) {
//       if (!update_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, $cleanedKey)) {
//         // Check if the key was changed at all - if not this is an error of update_user_meta
//         if (get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, True) != $cleanedKey) {
//           wp_die(__('Failed to update autologin link.', PKG_AUTOLOGIN_LANGUAGE_DOMAIN));
//         }
//       }
//     }
//   }

//   foreach($author_ids as $k => $user_id) {
//     if($k >= $start && $k <= $finish) {
//       if(get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY) && !get_user_meta($user_id, 'inactive_login_reminder_email_sent')) {
//         email_autologin_reminder_email($user_id);
//         add_user_meta($user_id, 'inactive_login_reminder_email_sent', date('Y-m-d H:i:s'));
//         var_dump($user_id . ': success');
//       } else {
//         var_dump($user_id . ': no success');
//       }
//     }
//   }
// }


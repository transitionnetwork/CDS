<?php
function update_user_and_post_metadata($user_id) {
  update_user_meta($user_id, 'last_logged_in', date('Y-m-d H:i:s'));

  $args = array(
    'post_type' => 'initiatives',
    'author' => $user_id,
    'posts_per_page' => -1,
    'post_status' => array('publish', 'pending', 'draft'),
    'fields' => 'ids'
  );

  $group_ids = get_posts($args);

  if($group_ids) {
    foreach($group_ids as $group_id) {
      update_post_meta($group_id, 'author_last_logged_in', date('Y-m-d H:i:s'));
    }
  }
}

function wp_login_update_metadata( $user_login, $user ) {
  //remove autologin code
  if(get_user_meta($user->ID, PKG_AUTOLOGIN_USER_META_KEY)) {
    delete_user_meta( $user->ID, PKG_AUTOLOGIN_USER_META_KEY);
  }
  
  update_user_and_post_metadata($user->ID);
  
}
add_action('wp_login', 'wp_login_update_metadata', 10, 2);

//switch to user logins
function switch_to_user_update_metadata($user_id) {
  update_user_and_post_metadata($user_id);
}
add_action( 'switch_to_user', 'switch_to_user_update_metadata', 10, 2 );
?>

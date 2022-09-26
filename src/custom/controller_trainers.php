<?php
function get_trainer_status($post_status) {
  $map = array(
    'publish' => array(
      'message' => 'Approved',
      'color' => 'primary'
    ),
    'pending' => array(
      'message' => 'Awaiting Approval',
      'color' => 'dark'
    )
  );

  return $map[$post_status];
}


function modify_trainer_query( $query ) {
  if($query->is_main_query() && !is_admin() && $query->is_post_type_archive) {
    if($query->query_vars['post_type'] === 'trainers' ) {
      $query->set('posts_per_page', -1);

      if(is_user_trainer_admin()) {
        $query->set('post_status', array('pending', 'publish'));
      }
    }
  }
}

add_action( 'pre_get_posts', 'modify_trainer_query' );

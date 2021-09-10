<?php
function trainers_get() {
  $args = array(
    'post_type' => 'trainers',
    'posts_per_page' => -1,
    'meta_query' => array(
      array(
        'key' => 'trainer_confirmation',
        'value' => true
      )
    )
  );

  if(is_user_trainer_admin()) {
    $args['post_status'] = array('pending', 'publish');
  }


  return new WP_Query($args);
}

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

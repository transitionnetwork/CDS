<?php 
function sync_vive_users() {
  $file = get_field('file_vive_links', 'options');
  $contents = file($file);
  $contents = array_map('trim', $contents);
  $contents = array_filter($contents);
  
  $new_contents = array();
  
  foreach($contents as $item) {
    $item = preg_replace("(^https?://)", "", $item );
    $result = explode('PHP_EOL', $item);
    $result = explode('    ', $item);
    
    foreach($result as $result_item) {
      $new_contents[] = $result_item;
    }
  }

  foreach($new_contents as $item) {
    $item = preg_replace("(^https?://)", "", $item );

    $args = array(
      'post_type' => 'initiatives',
      'posts_per_page' => -1,
      'post_status' => 'publish',
      'meta_query' => array(
        array(
          'key' => 'website',
          'value' => $item,
          'compare' => 'LIKE'
        ),
      ),
    );

    $posts = get_posts($args);
    if(count($posts) === 1) {
      update_post_meta( $posts[0] -> ID, 'vive', true);
    } else {
      update_post_meta( $posts[0] -> ID, 'vive', false);
    }
  }
}

if (!wp_next_scheduled('update_vive_status')) {
  wp_schedule_event(time(), 'eight_days', 'update_vive_status');
}
add_action('update_vive_status', 'sync_vive_users');

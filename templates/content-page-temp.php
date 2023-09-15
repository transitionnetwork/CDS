<?php
// clean_dev_log_entries();

function clean_dev_log_entries() {
  $args = array(
    'post_type' => 'initiatives',
    'meta_query' => array(
      array(
        'key' => 'mail_log',
        'compare' => 'EXISTS'
      )
    ),
    'posts_per_page' => -1
  );

  $posts = get_posts($args);
  if($posts) {
    // foreach($posts as $post) {
    //   var_dump(get_post_meta($post->ID, 'mail_log', true));
    // }
    // die();
    
    foreach($posts as $post) {
      $last_event = get_post_meta( $post->ID, 'last_mail_event' );
      $log = get_post_meta($post->ID, 'mail_log', true);
  
      var_dump($log);
    
    
      foreach($log as $key => $item) {
        if(str_contains($item, 'benewith') || str_contains($item, 'xinc')) {
          unset($log[$key]);
        }
      }
  
      $mail_log = array_values($log);
      update_post_meta( $post->ID, 'mail_log', $mail_log);
  
      $last_time = substr($log[0], 0, 19);
      update_post_meta( $post->ID, 'last_mail_date', $last_time);
    
      $split = explode(" ", $log[0]);
      $last_word = $split[count($split)-1];
      update_post_meta( $post->ID, 'last_mail_event', $last_word);
    }

    var_dump('complete');
  }
  
}

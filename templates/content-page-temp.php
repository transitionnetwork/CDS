<?php
  $args = array(
    'post_type' => array('initiatives', 'trainers'),
    'posts_per_page' => -1
  );
  $all_posts = get_posts($args);
  foreach ($all_posts as $single_post){
    $map = get_field('map', $single_post->ID);
    if($map && !empty($map['markers'])) {
      update_post_meta( $single_post->ID, 'cloned_lat', $map['markers'][0]['lat']);
      update_post_meta( $single_post->ID, 'cloned_lng', $map['markers'][0]['lng']);
    }
  }
?>


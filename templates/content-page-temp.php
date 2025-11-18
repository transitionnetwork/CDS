<?php
function remove_country_terms_from_trainer_posts() {
  $args = array(
    'post_type' => 'trainers',
    'posts_per_page' => -1
  );

  $posts = get_posts($args);

  foreach($posts as $post) {
    wp_set_post_terms($post->ID, array(), 'country');
    var_dump('Removed country terms from post ID: ' . $post->ID);
  }
}

remove_country_terms_from_trainer_posts();

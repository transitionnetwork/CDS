<?php
function endpoint_get_trainers($request) {
  $data = [];
  $default_per_page = 20;
  
  $args = array(
    'post_type' => 'trainers',
    'posts_per_page' => $default_per_page
  );

  $params_args = endpoint_get_params_args($request);
  $args = array_merge($args, $params_args);

  $post_query = new WP_Query($args);

  if($post_query->have_posts()) {
    while($post_query->have_posts()) : $post_query->the_post();
      global $post;
      $photo = get_field('general_information_trainer_photo', $post);
      $photo = ($photo) ? $photo['sizes']['large'] : '';
      
      $data[] = array(
        'title' => $post->post_title,
        'url' => get_the_permalink($post),
        'photo' => $photo,
        
        'hubs' => endpoint_get_taxonomy_terms($post, 'hub'),
        'countries' => endpoint_get_taxonomy_terms($post, 'country'),
        'topics' => endpoint_get_taxonomy_terms($post, 'trainer_topic'),
        'types' => endpoint_get_taxonomy_terms($post, 'trainer_type'),
        'languages' => endpoint_get_taxonomy_terms($post, 'trainer_language'),

        'biography' => get_field('general_information_trainer_bio', $post),
        
        'location' => endpoint_get_location($post, null, 'general_information_location'),
        'website' => get_field('your_website', $post),
      );
    endwhile;
  }

  if(!empty($data)) {
    $pagination = endpoint_get_pagination($post_query);

    return array_merge(array('body' => $data), $pagination);
  } else {
    return array(
      'body' => 'No Records Found'
    );
  }
}

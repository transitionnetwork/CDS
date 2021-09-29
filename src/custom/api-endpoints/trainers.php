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
      $photo = get_field('your_website_listing_training_photo', $post);
      $photo = ($photo) ? $photo['url'] : '';
      
      $data[] = array(
        'title' => $post->post_title,
        'url' => get_the_permalink($post),
        'photo' => $photo,
        
        'hubs' => endpoint_get_taxonomy_terms($post, 'hub'),
        'countries' => endpoint_get_taxonomy_terms($post, 'country'),
        'topics' => endpoint_get_taxonomy_terms($post, 'trainer_topic'),
        'types' => endpoint_get_taxonomy_terms($post, 'trainer_type'),
        'languages' => endpoint_get_taxonomy_terms($post, 'trainer_language'),

        'biography' => get_field('your_website_listing_training_bio', $post),
        'coaching_format_detail' => get_field('training_information_training_format_detail', $post),
        'regional_focus' => get_field('training_information_training_regions', $post),
        'training_undertaken' => get_field('training_information_training_other_organisations', $post),
        
        'location' => endpoint_get_location($post),
        'contact' => endpoint_get_contact($post),
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

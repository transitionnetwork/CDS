<?php
function endpoint_get_initiatives($request) {
  $data = [];
  $default_per_page = 20;

  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => $default_per_page
  );

  $params_args = endpoint_get_params_args($request);
  $args = array_merge($args, $params_args);

  $post_query = new WP_Query($args);

  if($post_query->have_posts()) {
    while($post_query->have_posts()) : $post_query->the_post();
      global $post;
      
      $logo = get_field('logo', $post);
      $logo = ($logo) ? $logo['url'] : '';
  
      $data[] = array(
        'title' => $post->post_title,
        'url' => get_the_permalink($post),
        'logo' => $logo,
  
        'hubs' => endpoint_get_taxonomy_terms($post, 'hub'),
        'countries' => endpoint_get_taxonomy_terms($post, 'country'),
        'topics' => endpoint_get_taxonomy_terms($post, 'topic'),
  
        'description' => apply_filters('the_content', get_post_field('post_content', $post->ID)),
        
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

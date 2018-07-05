<?php while (have_posts()) : the_post();
  $author_object = get_user_by('id', get_the_author_id());
  $author_hub_name = get_the_terms($author_object, 'hub')[0]->name;
  $author_hub_id = get_the_terms($author_object, 'hub')[0]->term_id;
  echo 'Hub: ' . $author_hub_name;

  $users = get_objects_in_term($author_hub_id, 'hub');

  $args = array(
    'post_type' => 'initiatives',
    'author__in' => $users,
    'posts_per_page' => -1
  );

  $posts = get_posts($args);
  
  foreach ($posts as $post) :
    setup_postdata( $post );
    echo '<pre>';
    echo 'build the map here';
    echo '</pre>';
  endforeach;
endwhile;

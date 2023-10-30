<?php
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'post_status' => array('publish', 'pending'),
  'fields' => 'ids',
  'tax_query' => array(
    array(
      'taxonomy' => 'co_author',
      'terms' => get_current_user_id(),
      'field' => 'slug'
    )
  )
);

$init_query = new WP_Query($args); ?>

<section>
  <h2><?php _e('Groups co-authored by me', 'tofino'); ?></h2>

  <?php
  if($init_query->have_posts()) { 
    set_query_var('init_query', $init_query);
    get_template_part('templates/tables/initiatives');
  } else {
    _e('You\'re not currently a co-author of any group', 'tofino');
  }
  ?>
</section>

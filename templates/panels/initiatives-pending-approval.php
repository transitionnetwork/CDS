<?php 
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'post_status' => 'pending',
  'fields' => 'ids'
);

$init_query = new WP_Query($args); ?>


<section>
  <h2><?php _e('All groups pending approval', 'tofino'); ?></h2>

  <?php
  if($init_query->have_posts()) { 
    set_query_var('init_query', $init_query);
    get_template_part('templates/tables/initiatives');
  } else {
    e('There aren\'t any groups pending approval', 'tofino');
  }
  ?>

</section>


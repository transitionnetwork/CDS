<?php

$args = array(
  'post_type' => 'initiatives',
  'author' => wp_get_current_user()->ID,
  'posts_per_page' => -1,
  'post_status' => array('publish', 'pending'),
  'fields' => 'ids'
);

$init_query = new WP_Query($args); ?>

<section>
  <h2><?php _e('Initiatives created by me', 'tofino'); ?></h2>

  <?php
  if($init_query->have_posts()) { 
    set_query_var('init_query', $init_query);
    get_template_part('templates/tables/initiatives');
  } else {
    _e('You haven\'t added any initiatives yet', 'tofino');
  }
  ?>

  <div class="button-block"><a href="/add-initiative" class="btn btn-primary btn-sm"><?php echo svg('plus'); ?><?php _e('Add New Initiative', 'tofino'); ?></a></div>
</section>

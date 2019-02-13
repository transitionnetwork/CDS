<?php 
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'post_status' => 'pending',
  'fields' => 'ids'
);
$post_ids = get_posts($args); ?>
<section>
  <h2><?php _e('All Initiatives pending approval', 'tofino'); ?></h2>
  <?php if ($post_ids) :
    list_initiatives($post_ids);
  else : ?>
  <?php _e('There aren\'t any initiatives pending approval', 'tofino'); ?>
  <?php endif; ?>
</section>


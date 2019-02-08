<?php 
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'post_status' => 'pending'
);
$posts = get_posts($args); ?>
<section>
  <h2><?php _e('All Initiatives pending approval', 'tofino'); ?></h2>
  <?php if ($posts) :
    list_initiatives($posts);
  else : ?>
  <?php _e('There aren\'t any initiatives pending approval', 'tofino'); ?>
  <?php endif; ?>
</section>


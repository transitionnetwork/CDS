<?php 
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'post_status' => 'pending'
);
$posts = get_posts($args); ?>
<section>
  <h2>All Initiatives pending approval</h2>
  <?php if ($posts) :
    list_initiatives($posts);
  else : ?>
  There aren't any initiatives pending approval.
  <?php endif; ?>
</section>


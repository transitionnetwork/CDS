<?php 
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'post_status' => 'pending'
);
$posts = get_posts($args); ?>
<section>
  <h2>Initiatives pending approval</h2>
  <?php if ($posts) :
    list_initiatives($posts);
  else : ?>
  There aren't any initiatives pending approval for the <?php echo $user_hub_name; ?> hub.
  <?php endif; ?>
</section>


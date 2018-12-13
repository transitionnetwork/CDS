<?php 
$users = get_hub_users(get_user_hub_id());
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'post_status' => 'pending',
  'author__in' => $users
);
$posts = get_posts($args); ?>
<section>
  <h2>Initiatives pending approval in <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?></h2>
  <?php if ($posts) :
    list_initiatives($posts);
  else : ?>
  There aren't any initiatives pending approval for the  <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?> hub.
  <?php endif; ?>
</section>


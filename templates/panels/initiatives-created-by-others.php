<?php 
$users = get_hub_users(get_user_hub_id());

$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'author__in' => $users
);
$posts = get_posts($args); ?>

<section>
  <h2><?php _e('All initatives created for', 'tofino'); ?> <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?></h2>
  <?php if ($posts) :
    list_initiatives($posts);
  else : ?>
    <?php _e('There haven\'t been any other initiatives added for this hub', 'tofino'); ?>
  <?php endif; ?>
</section>

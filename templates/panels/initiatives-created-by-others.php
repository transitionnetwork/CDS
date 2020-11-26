<?php 
//this doesn't seem to be in use
$users = get_hub_users(get_user_hub_slug(get_current_user_id()));

$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'author__in' => $users,
  'fields' => 'ids'
);
$post_ids = get_posts($args); ?>

<section>
  <h2><?php _e('All initatives created for', 'tofino'); ?> <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?></h2>
  <?php if ($post_ids) :
    list_initiatives($post_ids);
  else : ?>
    <?php _e('There haven\'t been any other initiatives added for this hub', 'tofino'); ?>
  <?php endif; ?>
</section>

<?php 
$users = get_hub_users(get_user_hub_id());

$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'author__in' => $users
);
$posts = get_posts($args); ?>

<section>
  <h2>All initatives created in <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?></h2>
  <?php if ($posts) :
    list_initiatives();
  else : ?>
    Other hub users haven't added any initiatives yet
  <?php endif; ?>
</section>

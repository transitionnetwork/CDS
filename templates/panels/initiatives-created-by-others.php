<?php 
//this doesn't seem to be in use
$users = get_hub_users(get_user_hub_slug(get_current_user_id()));

$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'author__in' => $users,
  'fields' => 'ids'
);
$init_query = new WP_Query($args);

?>

<section>
  <h2><?php _e('All initatives created for', 'tofino'); ?> <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?></h2>

  <?php
  if($init_query->have_posts()) { 
    set_query_var('init_query', $init_query);
    get_template_part('templates/tables/initiatives');
  } else {
    e('There haven\'t been any other initiatives added for this hub', 'tofino');
  }
  ?>
</section>

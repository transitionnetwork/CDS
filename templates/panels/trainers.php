<?php
$args = array(
  'author' => get_current_user_id(),
  'post_type' => 'trainers',
);

$my_trainer_posts = get_posts($args);
?>

<section>
  <?php if(is_array($my_trainer_posts) && !empty($my_trainer_posts)) { ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
      <?php foreach($my_trainer_posts as $post) { ?>
        <?php setup_postdata( $post ); ?>
        <?php get_template_part('templates/partials/trainer-item'); ?>
      <?php } ?>
      <?php wp_reset_postdata(); ?>
    </div>
  <?php } else { ?>
    <?php _e('No trainer profiles created', 'tofino'); ?>
  <?php } ?>
</section>

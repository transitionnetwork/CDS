<?php $trainers_query = trainers_get(); ?>

<div class="container">
  <h1>Trainers</h1>
  <?php if(is_user_logged_in()) { ?>
    <p>
      <a class="btn btn-primary btn-sm" href="<?php echo parse_post_link(6739); ?>"><?php echo svg('plus'); ?><?php _e('Add New Trainer', 'tofino'); ?></a>
    </p>
  <?php } ?>
  <?php if ( $trainers_query->have_posts() ) : ?>
    <div class="row mt-4">
      <?php while ( $trainers_query->have_posts() ) : $trainers_query->the_post(); ?>
        <?php get_template_part('templates/partials/trainer-item'); ?>
      <?php endwhile; ?>
    </div>
  <?php else : ?>
    <?php _e('There are no trainers found'); ?>
  <?php endif; ?>
</div>

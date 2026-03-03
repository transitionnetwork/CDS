<?php set_query_var('type', '4'); ?>

<?php get_template_part('templates/partials/map-display'); ?>

<main>
  <div class="container">
    <h1>Trainers</h1>
    <?php if(is_user_logged_in() && is_user_role(array('super_hub', 'administrator'))) { ?>
      <p>
        <a class="btn btn-primary btn-sm" href="<?php echo get_the_permalink(6739); ?>"><?php echo svg('plus'); ?><?php _e('Add New Trainer', 'tofino'); ?></a>
        <a class="btn btn-primary btn-sm" href="<?php echo get_the_permalink(7097); ?>"><?php echo svg('cloud-download'); ?>Export CSV of trainer data</a>
      </p>
    <?php } ?>
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mt-6">
      <div class="lg:col-span-3">
        <div>
          <h3>Language</h3>
          <?php echo do_shortcode('[facetwp facet="trainer_language"]'); ?>
        </div>
        <div>
          <h3>Region</h3>
          <?php echo do_shortcode('[facetwp facet="trainer_country"]'); ?>
        </div>
        <div>
          <h3>Course</h3>
          <?php echo do_shortcode('[facetwp facet="trainer_course"]'); ?>
        </div>
      </div>
      <div class="lg:col-span-9">
        <?php if ( have_posts() ) : ?>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <?php while ( have_posts() ) : the_post(); ?>
              <?php get_template_part('templates/partials/trainer-item'); ?>
            <?php endwhile; ?>
          </div>
        <?php else : ?>
          <?php _e('There are no trainers found'); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

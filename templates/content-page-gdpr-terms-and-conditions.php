<main>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8">
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <?php if(is_user_logged_in() && !get_user_meta($user->ID, 'gdpr_accepted')) { ?>
            <div class="scrollbox">
              <?php the_content(); ?>
            </div>
            <form id="gdpr" action="<?php the_permalink(); ?>" method="POST">
              <input type="hidden" name="accepted" value="true">
              <input type="submit" value="<?php _e('Accept', 'tofino'); ?>">
            </form>
          <?php } else {
            the_content();
          } ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

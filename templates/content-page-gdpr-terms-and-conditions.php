<?php $user = wp_get_current_user(); ?>
<main>
  <div class="container">
    <div class="mx-auto max-w-3xl w-full">
      <div>
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <?php if(is_user_logged_in() && !get_user_meta($user->ID, 'gdpr_accepted')) { ?>
            <div class="scrollbox rich-text">
              <?php the_content(); ?>
            </div>
            <form id="gdpr" action="<?php the_permalink(); ?>" method="POST">
              <input type="hidden" name="accepted" value="true">
              <input type="submit" value="<?php _e('Accept', 'tofino'); ?>">
            </form>
          <?php } else { ?>
            <div class="rich-text"><?php the_content(); ?></div>
          <?php } ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

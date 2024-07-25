<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php the_content(); ?>
      <?php _e('Logged in as', 'tofino'); ?> <?php echo wp_get_current_user()->user_email; ?> | <a href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', 'tofino'); ?></a>
    <?php endwhile; ?>
  </div>
</main>

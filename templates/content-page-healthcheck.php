<main>
  <div class="container">
    <div class="flex justify-center">
      <div class="w-full max-w-3xl">
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <p><a href="<?php echo get_field('healthcheck_pdf') ?>" onclick="gtag('event', 'PDF Download', {'event_category' : 'downloads', 'event_label' : '<?php echo get_locale(); ?>' });">
            <img src="<?php echo get_field('healthcheck_image')['url']; ?>" alt="<?php echo get_the_title(); ?>">
          </a></p>
          <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

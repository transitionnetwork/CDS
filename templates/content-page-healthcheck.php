<main>
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-8">
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <p><a href="<?php echo get_field('healthcheck_pdf') ?>" onclick="gtag('event', 'PDF Download', {'event_category' : 'downloads', 'event_label' : '<?php echo ICL_LANGUAGE_CODE; ?>' });">
            <img src="<?php echo get_field('healthcheck_image')['url']; ?>" alt="<?php echo get_the_title(); ?>">
          </a></p>
          <?php the_content(); ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

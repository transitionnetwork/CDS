<main>
  <div class="container">
    <div class="flex flex-wrap">
      <div class="w-full lg:w-8/12">
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <p><a href="<?php echo get_field('healthcheck_pdf') ?>" onclick="gtag('event', 'PDF Download', {'event_category' : 'downloads', 'event_label' : '<?php echo get_locale(); ?>' });">
            <img src="<?php echo get_field('healthcheck_image')['url']; ?>" alt="<?php echo get_the_title(); ?>">
          </a></p>
          <div class="rich-text"><?php the_content(); ?></div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

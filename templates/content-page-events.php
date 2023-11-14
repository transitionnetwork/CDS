<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php get_template_part('templates/partials/events/list-events'); ?>
    <?php endwhile; ?>
  </div>
</main>

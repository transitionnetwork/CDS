<main>
  <div class="container">
    <div>
      <div>
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <div class="rich-text"><?php the_content(); ?></div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

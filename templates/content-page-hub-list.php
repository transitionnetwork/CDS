<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php $args = array(
        'taxonomy' => 'hub',
        'hide_empty' => false,
        'exclude' => array(285)
      ); ?>
      <?php $hubs = get_terms($args); ?>
      <?php if($hubs) { ?>
        <div class="row">
          <?php foreach ($hubs as $hub) { ?> 
            <?php set_query_var('hub', $hub); ?>
            <?php get_template_part('templates/partials/tile-hub'); ?>
          <?php } ?>
        </div>
      <?php } ?>
    <?php endwhile; ?>
    <p><a href="<?php echo parse_post_link(6185); ?>">Add new hub<p></a>
  </div>
</main>

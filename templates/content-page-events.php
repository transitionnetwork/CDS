<?php $api_url = get_field('pretix_api_url'); ?>
<?php $api_key = get_field('pretix_api_key'); ?>
<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php $events = xinc_events_get_events($api_url, $api_key); ?>
      <?php get_template_part('templates/partials/events/table-events', null, array('events' => $events)); ?>
    <?php endwhile; ?>
  </div>
</main>

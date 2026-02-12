<?php set_query_var('type', '3'); ?>

<?php get_template_part('templates/partials/map-display'); ?>

<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php $args = array(
        'taxonomy' => 'hub',
        'hide_empty' => false,
        'exclude' => array(285, 284)
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
    <?php if(is_user_logged_in()) { ?>
      <?php $hub_register_url = get_field('hub_signup_link', 'options'); ?>
      <a href="<?php echo ($hub_register_url) ? $hub_register_url : get_the_permalink(6185); ?>"  target="<?php echo ($hub_register_url) ? 'blank' : null; ?>" class="btn  btn-outline"><?php echo svg('plus'); ?><?php _e('Add New Hub', 'tofino'); ?></a>
    <?php } ?>
  </div>
</main>

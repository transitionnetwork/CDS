<div id="map-info">
  <a href="<?php echo home_url(); ?>" target="_parent">
    <span class="sr-only">Transiton Network website</span>
    <?php echo svg(array('sprite' => 'tn-network-25', 'class' => 'logo')); ?>
  </a>
  <div id="map-info-panel">
    <button class="close" aria-label="close panel">
      <span class="sr-only">close panel</span>
      <?php echo svg('x'); ?></button>
    <?php get_template_part('templates/partials/key'); ?>
  </div>
</div>

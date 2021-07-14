<div id="map-info">
  <a href="<?php echo home_url(); ?>" target="_parent"><?php echo svg(array('sprite' => 'tn-logo', 'class' => 'logo')); ?></a>
  <div id="map-info-panel">
    <button class="close"><?php echo svg('x'); ?></button>
    <?php get_template_part('templates/partials/key'); ?>
  </div>
</div>

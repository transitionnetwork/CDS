<?php get_header('map'); ?>

<div id="iframe_map">
  <a href="<?php echo home_url(); ?>" target="_top"><?php echo svg(array('sprite' => 'tn-logo', 'class' => 'logo')); ?></a>
  <?php get_template_part('templates/partials/map-panel'); ?>
  <div class="map-loading"><div class="lds-dual-ring"></div></div>
</div>
<?php get_template_part('templates/partials/map-filter'); ?>

<?php get_footer('map'); ?>

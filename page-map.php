<?php get_header('map'); ?>
<?php if(get_query_var('hub_id')) { ?>
  <?php $hub = get_term_by('id', get_query_var('hub_id'), 'hub'); ?>
  <div id="iframe_map" data-hub="<?php echo $hub->slug; ?>">
    <?php get_template_part('templates/partials/key'); ?>
    <a href="<?php echo home_url(); ?>" target="_top"><?php echo svg(array('sprite' => 'tn-logo', 'class' => 'logo')); ?></a>
    <div id="map-loading"><div class="lds-dual-ring"></div></div>
  </div>
<?php } else { ?>
  <?php _e('Hub ID is missing'); ?>
<?php } ?>

<?php get_footer('map'); ?>

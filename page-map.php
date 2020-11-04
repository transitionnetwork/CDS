<?php get_header('map'); 

$query_value = get_query_var('hub_id');
if($query_value) {
  $hub_ids = explode(',', $query_value);

  $slugs = [];
  foreach($hub_ids as $hub_id) {
    $hub_term = get_term_by('id', $hub_id, 'hub');
    $slugs[] = $hub_term->slug;
  }
  
  $slugs = implode(',', $slugs); ?>

<?php } ?>

<div id="iframe_map" data-hub="<?php echo ($slugs) ? $slugs : null; ?>">
  <a href="<?php echo home_url(); ?>" target="_top"><?php echo svg(array('sprite' => 'tn-logo', 'class' => 'logo')); ?></a>
  <?php get_template_part('templates/partials/map-panel'); ?>
  <div class="map-loading"><div class="lds-dual-ring"></div></div>
</div>

<?php get_footer('map'); ?>

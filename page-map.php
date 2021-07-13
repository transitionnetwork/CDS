<?php get_header('map'); ?>

<div id="iframe_map">
  <?php get_template_part('templates/partials/map-panel'); ?>
  <?php get_template_part('templates/partials/map-filter'); ?>
  <div class="map-loading"><div class="lds-dual-ring"></div></div>
</div>


<?php get_footer('map'); ?>

<div id="map-wrapper" class="relative">
  <div id="map-iframe">
    <?php get_template_part('templates/partials/map-panel'); ?>
    <div id="map-no-results" style="display: none">No Results</div>
  </div>
  <div id="map-loading" class="absolute inset-0 z-500 flex items-center justify-center bg-base-100/60 pointer-events-none">
    <span class="loading loading-spinner loading-lg text-brand-primary"></span>
  </div>
  <?php get_template_part('templates/partials/map-filter'); ?>
</div>

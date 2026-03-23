<?php set_query_var('type', '4'); ?>

<?php get_template_part('templates/partials/map-display'); ?>

<main>
  <div class="container">
    <h1>Trainers</h1>
    <?php if(is_user_logged_in() && is_user_role(array('super_hub', 'administrator'))) { ?>
      <p>
        <a class="btn btn-primary btn-sm" href="<?php echo get_the_permalink(6739); ?>"><?php echo svg('plus'); ?><?php _e('Add New Trainer', 'tofino'); ?></a>
        <a class="btn btn-primary btn-sm" href="<?php echo get_the_permalink(7097); ?>"><?php echo svg('cloud-download'); ?>Export CSV of trainer data</a>
      </p>
    <?php } ?>
    <?php // Mobile filter drawer ?>
    <div class="drawer drawer-end lg:hidden">
      <input id="trainer-filters-drawer" type="checkbox" class="drawer-toggle" />
      <div class="drawer-content">
        <label for="trainer-filters-drawer" class="btn btn-sm btn-outline mb-4">
          <?php echo svg('filter'); ?>Filters
        </label>
      </div>
      <div class="drawer-side z-1100">
        <label for="trainer-filters-drawer" aria-label="close sidebar" class="drawer-overlay"></label>
        <div class="bg-base-200 min-h-full w-80 p-4">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold m-0">Filters</h3>
            <label for="trainer-filters-drawer" class="btn btn-ghost btn-circle text-xl">&times;</label>
          </div>
          <div class="flex flex-col gap-3">
            <?php get_template_part('templates/partials/trainer-filters'); ?>
          </div>
        </div>
      </div>
    </div>

    <div class="flex flex-col lg:flex-row mt-6 gap-6">
      <?php // Desktop sidebar ?>
      <div class="hidden lg:block lg:w-3/12">
        <div class="card bg-base-200 border border-base-content/10">
          <div class="card-body p-4 gap-3">
            <?php get_template_part('templates/partials/trainer-filters'); ?>
          </div>
        </div>
      </div>

      <div class="w-full lg:w-9/12">
        <?php if ( have_posts() ) : ?>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php while ( have_posts() ) : the_post(); ?>
              <?php get_template_part('templates/partials/trainer-item'); ?>
            <?php endwhile; ?>
          </div>
        <?php else : ?>
          <?php _e('There are no trainers found'); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

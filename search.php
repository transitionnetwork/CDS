<?php get_header(); ?>

<main>
  <div class="container">

    <div class="flex flex-col lg:flex-row justify-between gap-6">
      <aside class="w-full lg:w-3/12 lg:order-2">
        <?php get_template_part('templates/partials/sidebar-search'); ?>
        <?php get_template_part('templates/partials/sidebar-group-stats', null, array('view' => 'list')); ?>
        <?php get_template_part('templates/partials/sidebar-buttons'); ?>
      </aside>
      
      <div class="w-full lg:w-9/12 lg:order-1">

    
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>

        <?php $init_query = get_initiatives_main(); ?>
        <?php set_query_var('init_query', $init_query); ?>
        <?php get_template_part('templates/tables/initiatives'); ?>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>

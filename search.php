<?php get_header(); ?>

<main>
  <div class="container-fluid">

    <div class="row justify-content-lg-between">
      <div class="col-12 col-lg-3 order-lg-2">
        <?php get_template_part('templates/partials/group-list-sidebar'); ?>
      </div>
      
      <div class="col-12 col-lg-9 order-lg-1">

    
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>

        <?php $init_query = get_initiatives_main(); ?>
        <?php set_query_var('init_query', $init_query); ?>
        <?php get_template_part('templates/tables/initiatives'); ?>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>

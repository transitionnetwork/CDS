<?php get_template_part('templates/partials/map-display'); ?>

<main>
  <div class="container-fluid">

    <div class="row justify-content-lg-between">
      <div class="col-12 col-lg-3 order-lg-2">
        <?php get_template_part('templates/partials/group-list-sidebar'); ?>
      </div>
      
      <div class="col-12 col-lg-9 order-lg-1">
        <?php if(get_query_var('added_note')) { ?>
          <div class="alert top alert-success">
            <?php _e('Your note has been added', 'tofino'); ?>
          </div>
        <?php } ?>

        <?php if (get_query_var('hub_name')) :
          $term = get_term_by('slug', get_query_var('hub_name'), 'hub');
          echo '<h1>Hub: ' . $term->name . '</h1>';
          echo $term->description;
        endif; ?>
        
        <?php if (get_query_var('country')) :
          $term = get_term_by('slug', get_query_var('country'), 'country');
          echo '<h1>Country: ' . $term->name . '</h1>';
          echo $term->description;
        endif; ?>
    
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>

        <?php while (have_posts()) : the_post(); ?>
          <?php if (!is_user_logged_in() && !empty(get_the_content())) { ?>
            <div class="panel mt-3">
              <?php the_content(); ?>
            </div>
          <?php } ?>
        <?php endwhile; ?>

        <?php $init_query = get_initiatives_main(); ?>
        <?php set_query_var('init_query', $init_query); ?>
        <?php get_template_part('templates/tables/initiatives'); ?>
      </div>
    </div>
  </div>
</main>

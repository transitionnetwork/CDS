<?php get_template_part('templates/partials/map-display'); ?>

<main>
  <div class="container">

    <?php while (have_posts()) : the_post(); ?>
      <?php if (!is_user_logged_in() && !empty(get_the_content())) { ?>
        <div class="panel mt-3">
          <?php the_content(); ?>
        </div>
      <?php } ?>
    <?php endwhile; ?>
    
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
    <?php $init_query = get_initiatives_main(); ?>
    <?php set_query_var('init_query', $init_query); ?>
    <?php get_template_part('templates/tables/initiatives'); ?>
    

    <ul class="button-group">
      <?php if(is_user_logged_in()) { ?>
        <li><a class="btn btn-primary" href="<?php echo parse_post_link(13); ?>"><?php echo svg('plus'); ?><?php _e('Add New Initiative', 'tofino'); ?></a></li>

        <li><a class="btn btn-primary" href="<?php echo parse_post_link(6739); ?>"><?php echo svg('plus'); ?><?php _e('Add New Trainer', 'tofino'); ?></a></li>
      <?php } else { ?>
        <li><a class="btn btn-primary" href="<?php echo parse_post_link(460); ?>"><?php echo svg('key'); ?><?php _e('Register to add an initiative', 'tofino'); ?></a></li>
      <?php } ?>
    </ul>
  </div>
</main>

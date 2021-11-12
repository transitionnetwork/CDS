<?php get_template_part('templates/partials/map-display'); ?>

<main>
  <div class="container-fluid">

    <div class="row justify-content-lg-between">
      <div class="col-12 col-lg-3 order-lg-2">
        <ul class="button-group">
          <?php if(is_user_logged_in()) { ?>
            <li><a class="btn btn-outline" href="<?php echo parse_post_link(13); ?>"><?php echo svg('plus'); ?><?php _e('Add New Group', 'tofino'); ?></a></li>
            <li><a class="btn btn-outline" href="<?php echo parse_post_link(6739); ?>"><?php echo svg('plus'); ?><?php _e('Add New Trainer', 'tofino'); ?></a></li>
          <?php } else { ?>
            <li>
              <?php _e('To add a group or hub please', 'tofino'); ?><br/>
              <a class="btn btn-outline" href="<?php echo parse_post_link(459); ?>"><?php echo svg('key'); ?><?php _e('Sign in', 'tofino'); ?></a>
              <?php _e('or', 'tofino'); ?>
              <a class="btn btn-outline" href="<?php echo parse_post_link(460); ?>"><?php echo svg('key'); ?><?php _e('Register', 'tofino'); ?></a>
            </li>
          <?php } ?>
            
          <?php if(is_user_logged_in()) { ?>
            <li><a href="<?php echo parse_post_link(6185); ?>" class="btn  btn-outline"><?php echo svg('plus'); ?><?php _e('Add New Hub', 'tofino'); ?></a></li>
          <?php } ?>
          
          <?php if(is_user_logged_in() && is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
            <li><a class="btn btn-outline" href="<?php echo parse_post_link(6810); ?>"><?php echo svg('key'); ?><?php _e('Register New User', 'tofino'); ?></a></li>
          <?php } ?>
        </ul>
      </div>
      
      <div class="col-12 col-lg-9 order-lg-1">
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

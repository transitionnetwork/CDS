<main>
  <div class="container">
    <?php $post_author = get_the_author_meta('ID'); ?>
    <h1><?php echo \Tofino\Helpers\title(); ?></h1>
    <?php $params = array('initiative_id' => get_the_ID()); ?>
    <div class="row justify-content-center">
      <div class="col-12 col-lg-8">
        <?php while (have_posts()) : the_post(); ?>

          <?php the_content(); ?>
          
          <div class="panel">
            <h3>Healthchecks</h3>
            <?php
            $args = array(
              'post_type' => 'healthchecks',
              'posts_per_page' => -1,
              'title' => get_the_ID(),
              'orderby' => 'post_date',
              'order' => 'DESC'
            );
            $healthchecks = get_posts($args);
            list_healthchecks($healthchecks);
            ?>
          </div>
          
          <a class="btn btn-primary" href="<?php echo add_query_arg($params, get_the_permalink(422)); ?>">Add Healthcheck</a>

          <?php var_dump('logo'); ?>

          
          <?php var_dump('email'); ?>
          <?php var_dump('website'); ?>
          <?php var_dump('twitter'); ?>
          <?php var_dump('facebook'); ?>
          <?php var_dump('instagram'); ?>
          <?php var_dump('youtube'); ?>
          <?php var_dump('additional_web_addresses'); ?>
          <?php var_dump('topic'); ?>
          
          <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' )) || is_super_hub_author_for_post($post_author)) : ?>
            <h2>Email and Health Check Results Information</h2>
            <?php var_dump('private_email'); ?>
          <?php else : ?>
            <strong>You do not have rights to view private data about this initiative</strong>
          <?php endif; ?>

          <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' )) || is_super_hub_author_for_post($post_author)) : ?>
            <?php $params = array('edit_post' => get_the_ID()); ?>
            <div class="button-block"><a class="btn btn-warning" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit this initiative</a></div>
            <div class="button-block"><a class="btn btn-danger" href="<?php echo get_delete_post_link(get_the_ID()); ?>">Delete this initiative</a></div>
          <?php endif; ?>

        <?php endwhile; ?>
      </div>
      <div class="col-12 col-lg-4">
        <?php echo get_field('map'); ?>
        <?php var_dump('address_line_1'); ?>
        <?php var_dump('city'); ?>
        <?php var_dump('province'); ?>
        <?php var_dump('postal_code'); ?>
        <?php var_dump('country'); ?>
      </div>
    </div>
  </div>
</main>

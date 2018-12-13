<?php while (have_posts()) : the_post(); ?>
  <main>
    <div class="container">
      <?php $post_author = get_the_author_meta('ID'); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php $params = array('initiative_id' => get_the_ID()); ?>
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">

            <?php the_content(); ?>

            <?php var_dump('logo'); ?>
            <?php var_dump('email'); ?>
            <?php var_dump('website'); ?>
            <?php var_dump('twitter'); ?>
            <?php var_dump('facebook'); ?>
            <?php var_dump('instagram'); ?>
            <?php var_dump('youtube'); ?>
            <?php var_dump('additional_web_addresses'); ?>
            <?php var_dump('topic'); ?>
            
            <?php if (can_view_healthcheck($post)) { ?>
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
              <a class="btn btn-primary btn-sm" href="<?php echo add_query_arg($params, get_the_permalink(422)); ?>">Add Healthcheck</a>
            </div>
          <?php } ?>
        </div>
        <div class="col-12 col-lg-4">
          <?php echo get_field('map'); ?>
          <?php if(can_view_healthcheck($post)) { ?>
            <?php var_dump('private_email'); ?>
          <?php } ?>
          <?php var_dump('address_line_1'); ?>
          <?php var_dump('city'); ?>
          <?php var_dump('province'); ?>
          <?php var_dump('postal_code'); ?>
          <?php var_dump('country'); ?>
        </div>
      </div>
      <?php if (can_publish_initiative($post) && !is_post_published($post)) {
        show_publish_button($post->ID);
      } ?>
      <?php //Check for initiative write ?>
      <div class="button-block"><a class="btn btn-warning btn-sm" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit this initiative</a></div>
      <div class="button-block"><a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link(get_the_ID()); ?>">Delete this initiative</a></div>
    </div>
  </main>
<?php endwhile; ?>

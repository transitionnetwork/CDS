<?php while (have_posts()) : the_post(); ?>
  <main>
    <div class="container">
      <?php $post_author = get_the_author_meta('ID'); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php $params = array('initiative_id' => get_the_ID()); ?>
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <?php $hub = get_hub_by_post($post); ?>
            <p><strong>Hub:</strong> <a href="<?php echo add_query_arg(array('term' => $hub->term_id), '/initiatives'); ?>"><?php echo $hub->name; ?></a></p>
            <p><?php var_dump('topic'); ?></p>

            <?php the_content(); ?>

            <?php if (get_field('website')) { ?>
              <label>Website:</label>
              <a href="<?php echo get_field('website'); ?>"><?php echo get_field('website'); ?></a>
            <?php } ?>
            <?php if (get_field('twitter')) { ?>
              <label>Twitter:</label>
              <a href="<?php echo get_field('twitter'); ?>"><?php echo get_field('twitter'); ?></a>
            <?php } ?>
            <?php if (get_field('facebook')) { ?>
              <label>Facebook:</label>
              <a href="<?php echo get_field('facebook'); ?>"><?php echo get_field('facebook'); ?></a>
            <?php } ?>
            <?php if (get_field('instagram')) { ?>
              <label>Instagram:</label>
              <a href="<?php echo get_field('instagram'); ?>"><?php echo get_field('instagram'); ?></a>
            <?php } ?>
            <?php if (get_field('youtube')) { ?>
              <label>YouTube:</label>
              <a href="<?php echo get_field('youtube'); ?>"><?php echo get_field('youtube'); ?></a>
            <?php } ?>


            <?php var_dump('additional_web_addresses'); ?>
            
            
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

          <img src="<?php echo get_field('logo')['url']; ?>">

          <?php if (get_field('address_line_1')) { ?>
              <label>Address:</label>
              <?php echo get_field('address_line_1'); ?><br/>
              <?php echo get_field('city'); ?><br/>
              <?php echo get_field('province'); ?><br/>
              <?php echo get_field('postal_code'); ?>
              <?php echo get_field('country'); ?><br/>
            <?php 
          } ?>

          <?php if (get_field('email')) { ?>
            <label>Email:</label>
            <a href="mailto:<?php echo get_field('email'); ?>"><?php echo get_field('email'); ?></a>
          <?php } ?>

          <?php if (can_view_healthcheck($post)) { ?>
            <?php if(get_field('private_email')) { ?>
              <label>Private Email:</label>
              <a href="mailto:<?php echo get_field('private_email'); ?>"><?php echo get_field('private_email'); ?></a>
            <?php } ?>
          <?php } ?>
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

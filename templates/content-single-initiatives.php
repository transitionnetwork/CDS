<?php while (have_posts()) : the_post(); ?>
  <main>
    <div class="container">
      <?php $post_author = get_the_author_meta('ID'); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
          <?php $topics = get_the_terms($post, 'topic');
          $topic_names = [];
          if($topics) {
            foreach ($topics as $topic) {
              $topic_names[] = $topic->name;
            } 
          } ?>

          <ul class="meta">
            <li><strong>Hub:</strong> <a href="<?php echo get_post_type_archive_link('initiatives') . '?term=' . get_field('hub_tax') ?>"><?php echo get_term(get_field('hub_tax'), 'hub')->name; ?></a></li>
            <?php if($topics) { ?>
              <li><strong>Topics:</strong> <?php echo implode(', ', $topic_names); ?></li>
            <?php } ?>
          </ul>

          <?php the_content(); ?>

          <?php if(get_field('website') || get_field('facebook') || get_field('instagram') || get_field('twitter') || get_field('youtube')) { ?>
            <section>
              <h4>Links</h4>
              <ul class="links">
                <?php if (get_field('website')) { ?>
                  <li><a href="<?php echo get_field('website'); ?>" target="_blank">Web</a></li>
                <?php } ?>
                <?php if (get_field('twitter')) { ?>
                  <li><a href="<?php echo get_field('twitter'); ?>" target="_blank"><?php echo svg('twitter'); ?></a></li>
                <?php } ?>
                <?php if (get_field('facebook')) { ?>
                  <li><a href="<?php echo get_field('facebook'); ?>" target="_blank"><?php echo svg('facebook'); ?></a></li>
                <?php } ?>
                <?php if (get_field('instagram')) { ?>
                  <li><a href="<?php echo get_field('instagram'); ?>" target="_blank"><?php echo svg('instagram'); ?></a></li>
                <?php } ?>
                <?php if (get_field('youtube')) { ?>
                  <li><a href="<?php echo get_field('youtube'); ?>" target="_blank"><?php echo svg('youtube'); ?></a></li>
                <?php } ?>
              </ul>
            </section>
          <?php } ?>

          <?php $additional = get_field('additional_web_addresses'); 
          if($additional) { ?>
            <section>
              <h4>More Links</h4>
              <ul>
                <?php foreach($additional as $item) { ?>
                  <li><a href="<?php echo $item['address']; ?>" target="_blank"><?php echo $item['label']; ?></a></li>
                <?php } ?>
              </ul>
            </section>
          <?php } ?>

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
              <p><a class="btn btn-primary btn-sm" href="<?php echo add_query_arg(array('initiative_id' => get_the_ID()), get_the_permalink(422)); ?>">Add Healthcheck</a></p>
            </div>
          <?php } ?>
        </div>
        <div class="col-12 col-lg-4">
          <?php echo get_field('map'); ?>
          <img src="<?php echo get_field('logo')['sizes']['large']; ?>">

          <?php if (get_field('address_line_1')) { ?>
              <label>Address:</label>
              <?php echo get_field('address_line_1'); ?><br/>
              <?php echo get_field('city'); ?><br/>
              <?php echo get_field('province'); ?><br/>
              <?php echo get_field('postal_code'); ?>
              <?php echo get_field('country'); ?><br/>
          <?php } ?>

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
      <?php if(can_write_initiative($post)) { ?>
        <div class="button-block"><a class="btn btn-warning btn-sm" href="<?php echo add_query_arg(array('edit_post' => get_the_ID()), '/edit-initiative'); ?>">Edit this initiative</a></div>
        <div class="button-block"><a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link(get_the_ID()); ?>">Delete this initiative</a></div>
      <?php } ?>
    </div>
  </main>
<?php endwhile; ?>

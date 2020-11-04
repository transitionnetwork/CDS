<?php acf_form_head(); ?>

<?php if(get_query_var('updated') == 'healthcheck') { ?>
  <div class="container">
    <div class="alert top alert-success">
     <?php echo get_post_field('post_content', 45); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('updated') == 'author') { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('The author of this initiative has been updated', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('edited_post')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('This initiative has been updated', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('added_post')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php if (wp_get_current_user()->roles[0] == 'initiative') {
        _e('Thank you for your submission. It is now awaiting approval by a hub user.', 'tofino');
      } else {
        _e('Thank you for your submission', 'tofino');
      } ?>
    </div>
  </div>
<?php } ?>

<?php while (have_posts()) : the_post(); ?>
  <main>
    <div class="container">
      <?php $post_author = get_the_author_meta('ID'); ?>
      <div class="row justify-content-between">
        <div class="col-12 col-lg-7">
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <?php $topics = get_the_terms($post, 'topic');
          $topic_names = [];
          if($topics) {
            foreach ($topics as $topic) {
              $topic_names[] = $topic->name;
            } 
          } ?>

          <ul class="meta">
            <?php $hub = wp_get_post_terms($post->ID, 'hub')[0]; ?>
            <li><strong>Hub: </strong> <a href="<?php echo get_term_link($hub); ?>"><?php echo $hub->name; ?></a></li>
            <?php if($topics) { ?>
              <li><strong><?php echo get_taxonomy('topic')->label; ?>:</strong> <?php echo implode(', ', $topic_names); ?></li>
            <?php } ?>
          </ul>

          <?php the_content(); ?>

          <?php if (can_publish_initiative($post) && !is_post_published($post)) {
            render_publish_button($post->ID);
          } ?>
          <?php if(can_write_initiative($post)) { ?>
            <div class="button-block"><a class="btn btn-warning btn-sm" href="<?php echo add_query_arg(array('edit_post' => get_the_ID()), '/edit-initiative'); ?>"><?php echo svg('pencil'); ?><?php _e('Edit this initiative', 'tofino'); ?></a></div>
            <div class="button-block"><a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link(get_the_ID()); ?>"><?php echo svg('trashcan'); ?><?php _e('Delete this initiative', 'tofino'); ?></a></div>
          <?php } ?>

          <?php if (can_view_healthcheck($post)) { ?>
            <div class="panel healthchecks">
              <h3><?php _e('Healthchecks', 'tofino'); ?></h3>
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
              <p><a class="btn btn-primary btn-sm" href="<?php echo add_query_arg(array('initiative_id' => get_the_ID()), parse_post_link(422)); ?>"><?php echo svg('plus'); ?><?php _e('Add Healthcheck', 'tofino'); ?></a></p>
            </div>
          <?php } ?>
        </div>
        <div class="col-12 col-lg-4">
          <aside>
            <?php $map = get_field('map'); ?>
            <?php set_query_var('map', $map); ?>
            <?php get_template_part('templates/partials/single-map'); ?>
            
            <?php if(get_field('logo')) { ?>
              <img src="<?php echo get_field('logo')['sizes']['large']; ?>">
            <?php } ?>
  
            <?php if (get_field('email')) { ?>
              <label><?php echo get_field_object('email')['label']; ?></label>
              <a href="mailto:<?php echo get_field('email'); ?>"><?php echo get_field('email'); ?></a>
            <?php } ?>

            <?php if(get_field('website') || get_field('facebook') || get_field('instagram') || get_field('twitter') || get_field('youtube')) { ?>
              <label><?php _e('Links', 'tofino'); ?></label>
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
            <?php } ?>

            <?php $additional = get_field('additional_web_addresses'); 
            if($additional) { ?>
              <section>
                <h4><?php _e('More Links', 'tofino'); ?></h4>
                <ul>
                  <?php foreach($additional as $item) { ?>
                    <li><a href="<?php echo $item['address']; ?>" target="_blank"><?php echo $item['label']; ?></a></li>
                  <?php } ?>
                </ul>
              </section>
            <?php } ?>

            <?php $post_author_id = get_the_author_meta('ID'); ?>
            <?php if (is_user_role('administrator') || is_user_role('super_hub') || is_user_role('hub')) { ?>
              <div class="panel mt-4">
                <h3>Author</h3>
                <label>Name</label><?php echo get_the_author_meta('display_name'); ?>
                <label>Email</label><?php echo get_the_author_meta('user_email'); ?>
              </div>
            <?php } ?>

            <?php if (is_user_role('administrator') || is_user_role('super_hub')) { ?>
              
              <form action="<?php the_permalink() ?>" method="POST" id="change-author" class="panel">
                <label for="authors">Update author</label>
                <?php $users = get_users(); ?>
                <p>
                  <select name="authors">
                    <?php foreach($users as $user) { ?>
                      <option value="<?php echo $user->ID; ?>" <?php echo ($user->ID === $post_author_id) ? 'selected' : ''; ?>><?php echo $user->display_name; ?> | <?php echo $user->user_email; ?></option>
                    <?php } ?>
                  </select>
                  <input name="post_id" type="hidden" value="<?php echo $post->ID; ?>">
                </p>
                <p>
                  <input type="submit" value="Change">
                </p>
              </form>
            <?php } ?>
  
            <?php if (can_view_healthcheck($post)) { ?>
              <?php if(get_field('private_email')) { ?>
                <label><?php echo get_field_object('private_email')['label']; ?>:</label>
                <a href="mailto:<?php echo get_field('private_email'); ?>"><?php echo get_field('private_email'); ?></a>
              <?php } ?>
            <?php } ?>
          </aside>
        </div>
      </div>
      
    </div>
  </main>
<?php endwhile; ?>

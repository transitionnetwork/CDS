<?php acf_form_head(); ?>

<?php $updated = get_query_var('updated'); ?>

<?php if($updated) { ?>
  <?php if($updated === 'healthcheck') { ?>
    <div class="container">
      <div class="alert top alert-success">
       <?php echo get_post_field('post_content', 45); ?>
      </div>
    </div>
  <?php } ?>
  
  <?php if($updated === 'author') { ?>
    <div class="container">
      <div class="alert top alert-success">
        <?php _e('The author of this group has been updated. The new author has been emailed', 'tofino'); ?>
        <?php custom_email_initaitive_author_updated(get_the_ID()) ?>
      </div>
    </div>
  <?php } ?>
<?php } ?>

<?php if(get_query_var('edited_post')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('This group has been updated', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('added_post')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php if (wp_get_current_user()->roles[0] === 'initiative') {
        _e('Thank you for your submission. It is now awaiting approval by a hub user.', 'tofino');
      } else {
        _e('Thank you for your submission', 'tofino');
      } ?>
    </div>
  </div>
<?php } ?>

<?php while (have_posts()) : the_post(); ?>
  <?php if(get_post_status() === 'draft') { ?>
    <div class="container">
      <div class="alert top alert-success">
        <?php _e('This group has now been removed and cannot be seen by the public.', 'tofino'); ?>
      </div>
    </div>
  <?php } ?>
  <main>
    <div class="container">
      <?php $post_author = get_the_author_meta('ID'); ?>
      <div class="row justify-content-between">
        <div class="col-12 col-lg-7">
          <div class="mb-3">
            <h1 class="mb-0"><?php echo \Tofino\Helpers\title(); ?></h1>
  
            <div>
              <em>Last Updated: <?php echo get_initiatve_age($post) . ' days ago'; ?></em>
            </div>
          </div>
          
          <?php echo get_field('description', $post); ?>

          <?php if(is_user_logged_in() && is_user_role(array('initiative'))) { ?>
            <?php $post_author = (int)$post->post_author; ?>
            <?php if($post_author !== get_current_user_id()) { // TODO: check for co-author access here and across site once plugin is installed ?>
              <?php if(!author_access_is_requested($post->ID)) { ?>
                <form action="" method="post" class="d-none">
                  <button class="btn btn-secondary btn-sm" name="request_post_access" value="<?php echo $post->ID; ?>"><?php echo svg('pencil'); ?>Request edit access</button>
                </form>
              <?php } else { ?>
                <p><strong>Edit access to this group has been requested.</strong></p>
              <?php } ?>
            <?php } ?>
          <?php } ?>

          <?php if (can_publish_initiative($post) && !is_post_published($post)) {
            render_publish_button($post->ID);
          } ?>
          <?php if(can_write_initiative($post)) { ?>
            <?php $confirm_message = __('Are you sure you want to remove this group?', 'tofino'); ?>
            <div class="button-block"><a class="btn btn-warning btn-sm" href="<?php echo add_query_arg(array('edit_post' => get_the_ID()), '/edit-group'); ?>"><?php echo svg('pencil'); ?><?php _e('Edit this group', 'tofino'); ?></a></div>
            <div class="button-block">
              <form action="" method="post">
              <button name="unpublish" value="<?php echo (get_the_ID()); ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?><?php _e('Delete', 'tofino'); ?></button>
              </form>
            </div>
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
            <?php if($map) { ?>
              <?php get_template_part('templates/partials/single-map'); ?>
            <?php } ?>
            
            <?php $logo = get_field('logo'); ?>

            <?php get_template_part('templates/partials/group-info-panel'); ?>

            <?php if (is_user_role(array('administrator', 'super_hub', 'hub') && can_write_initiative($post))) { ?>
              <?php get_template_part('templates/partials/update-author'); ?>
            <?php } ?>
          </aside>
        </div>
      </div>
      
    </div>
  </main>
<?php endwhile; ?>

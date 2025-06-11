<?php get_template_part('templates/partials/single-initiatives-messages'); ?>

<?php acf_form_head(); ?>

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
              <em>Last Updated: <?php echo get_initiatve_age($post)['days'] . ' days ago'; ?></em>
            </div>

            <?php if(get_post_meta( $post->ID, 'vive', true )) { ?>
              <div>
                <a class="btn-primary btn-sm" href="https://vive.transitiontogether.org.uk/s/transition-together/" target="_blank">Vive</a>
              </div>
            <?php } ?>

            <?php if(is_user_logged_in() && (is_user_role(array('super_hub', 'administrator')) || (is_user_role('hub') && is_post_in_user_hub($initiative_id)))) { ?>
              <?php $published_by_id = (int)get_post_meta( $post->ID, 'last_published_by', true); ?>
              <?php if($published_by_id) { ?>
                <?php $published_user = get_user_by('id', $published_by_id); ?>
                <div>
                  <em>Last published by: <?php echo get_user_meta( $published_user->ID, 'nickname', true ); ?> <strong>[<?php echo $published_by_id; ?>]</strong></em>
                </div>
              <?php } ?>
            <?php } ?>
          </div>

         <?php if(!is_post_published($post)) { ?>
            <div class="panel">
              <div class="status">
                <?php $pending_message = __('Group is unpublished', 'tofino'); ?>
                <span class="btn btn-sm btn-outline btn-disabled">
                  <?php echo svg('alert') . $pending_message; ?>
                </span>
              </div>
              
              <?php get_template_part('templates/buttons/publish-delete', null, array('post_id' => $post->ID)); ?>

            </div>
          <?php } ?>
          
          <?php get_template_part('templates/partials/group-info-panel'); ?>
          
          <?php echo strip_tags(get_field('description', $post), '<p><em><strong><u><ol><ul><li><blockquote>'); ?>


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

          <?php if(can_write_initiative($post)) { ?>
            <div class="button-block"><a class="btn btn-warning btn-sm" href="<?php echo add_query_arg(array('edit_post' => get_the_ID()), '/edit-group'); ?>"><?php echo svg('pencil'); ?><?php _e('Edit group', 'tofino'); ?></a></div>
            
            <?php if(get_post_status($post) === 'publish') { ?>
              <?php $confirm_message = __('Are you sure you want to unpublish this group? You can re-publish it from the Dashboard', 'tofino'); ?>
              <div class="button-block">
                <form action="" method="post">
                <button name="unpublish" value="<?php echo (get_the_ID()); ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?><?php _e('Unpublish group', 'tofino'); ?></button>
                </form>
              </div>
            <?php } ?>
          <?php } ?>

          <?php if (get_field('email')) { ?>
            <div class="mt-5">
              <h3>Contact <?php the_title(); ?></h3>
              <div id="group-name" class="d-none" data-name="<?php the_title(); ?>"></div>
              <div id="group-email" class="d-none" data-email="<?php echo get_field('email'); ?>"></div>
              <?php echo do_shortcode('[contact-form-7 id="971d56c" title="Group Contact Form"]'); ?>
            </div>  
          <?php } ?>

          <?php if(is_user_logged_in() && (is_user_role(array('super_hub', 'administrator')) || (is_user_role('hub') && is_post_in_user_hub($initiative_id)))) { ?>
            <div class="panel">
              <h3>Notes</h3>
              <?php get_template_part('templates/partials/note-list'); ?>
              <?php wp_reset_postdata(); ?>
              <p>
                <a class="btn btn-sm btn-outline" href="<?php echo add_query_arg(array('initiative_id' => get_the_ID()), '/add-note'); ?>">
                  <?php echo svg('plus'); ?>Add Note
                </a>
              </p>
            </div>
          <?php } ?>

          <?php $hubs = get_the_terms($post, 'hub'); ?>
          <?php if(is_user_logged_in() && can_edit_hub($hubs[0]->term_id)) { ?>
            <?php get_template_part('templates/panels/email-history'); ?>
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
            <?php if(is_user_logged_in() && can_write_initiative($post)) { ?>
              <div class="panel">
                <?php get_template_part('templates/partials/co-author-panel'); ?>
              </div>
            <?php } ?>

            <?php if (is_user_role(array('administrator', 'super_hub')) || (is_user_role('hub') && is_post_in_user_hub($initiative_id))) { ?>
              <?php get_template_part('templates/partials/primary-author-panel'); ?>
            <?php } ?>

            <?php get_template_part('templates/partials/group-contact-panel'); ?>
            
            <?php $map = get_field('map'); ?>
            <?php set_query_var('map', $map); ?>
            <?php if($map) { ?>
              <?php get_template_part('templates/partials/single-map'); ?>
            <?php } ?>
            
            <?php if (is_user_role(array('administrator', 'super_hub')) && can_write_initiative($post)  ) { ?>
              <?php get_template_part('templates/partials/grant-status'); ?>
            <?php } ?>
          </aside>
        </div>
      </div>
      
    </div>
  </main>
<?php endwhile; ?>

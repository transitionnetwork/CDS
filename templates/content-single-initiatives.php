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
      <div class="flex flex-col lg:flex-row justify-between gap-6">
        <div class="w-full lg:w-7/12">
          <div class="mb-6">
            <h1 class="mb-2"><?php echo \Tofino\Helpers\title(); ?></h1>

            <?php
              $country_term = (get_the_terms($post, 'country')) ? get_the_terms($post, 'country')[0] : null;
              $hub_terms = get_the_terms($post, 'hub');
              $hub_term = ($hub_terms && count($hub_terms) === 1) ? $hub_terms[0] : null;
            ?>

            <div class="flex flex-col gap-1.5">
              <?php // Row 1: Country, Hub ?>
              <div class="flex flex-wrap items-center gap-2">
                <?php if($country_term) { ?>
                  <span class="badge badge-outline badge-primary"><?php _e('Country', 'tofino'); ?>: <?php echo $country_term->name; ?></span>
                <?php } ?>
                <?php if($hub_term) { ?>
                  <a href="<?php echo get_term_link($hub_term); ?>" class="badge badge-outline badge-primary no-underline"><?php _e('Hub', 'tofino'); ?>: <?php echo $hub_term->name; ?></a>
                <?php } ?>
                <?php if(get_post_meta($post->ID, 'vive', true)) { ?>
                  <a class="badge badge-primary no-underline" href="https://vive.transitiontogether.org.uk/s/transition-together/" target="_blank">Vive</a>
                <?php } ?>
                <?php if(!is_post_published($post)) { ?>
                  <span class="badge badge-warning gap-1 whitespace-nowrap"><?php echo svg(['sprite' => 'alert', 'class' => 'size-4 shrink-0']); ?> <?php _e('Group is unpublished', 'tofino'); ?></span>
                <?php } ?>
              </div>

              <?php // Row 2: Created, Updated, Healthcheck ?>
              <div class="flex flex-wrap items-center gap-2">
                <?php if(is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
                  <span class="badge badge-outline badge-neutral"><?php _e('Created', 'tofino'); ?>: <?php echo get_the_date('j-M-Y'); ?></span>
                <?php } ?>
                <span class="badge badge-outline badge-neutral"><?php _e('Updated', 'tofino'); ?>: <?php echo get_initiatve_age($post)['days'] . ' days ago'; ?></span>
                <?php if(is_user_logged_in() && can_view_healthcheck($post)) { ?>
                  <span class="badge badge-outline badge-secondary"><?php _e('Last Healthcheck', 'tofino'); ?>: <?php echo get_latest_healthcheck($post); ?></span>
                <?php } ?>
              </div>

              <?php // Row 3: Last Email + Email Event (admin/hub only) ?>
              <?php if(is_user_role(array('administrator', 'super_hub', 'hub')) && $hub_term && can_edit_hub($hub_term->term_id)) { ?>
                <div class="flex flex-wrap items-center gap-2">
                  <span class="badge badge-outline"><?php _e('Last Email', 'tofino'); ?>: <?php echo get_post_meta($post->ID, 'last_mail_date', true) ?: '—'; ?></span>
                  <?php
                    $mail_event = get_post_meta($post->ID, 'last_mail_event', true);
                    $mail_event_display = $mail_event ? ucwords($mail_event) : '—';
                    $mail_event_color = (strtolower($mail_event) === 'delivered') ? 'badge-success' : 'badge-error';
                    if(!$mail_event) { $mail_event_color = 'badge-outline'; }
                  ?>
                  <span class="badge badge-outline <?php echo $mail_event_color; ?>"><?php _e('Email Event', 'tofino'); ?>: <?php echo $mail_event_display; ?></span>
                </div>
              <?php } ?>
            </div>

            <?php if(is_user_logged_in() && (is_user_role(array('super_hub', 'administrator')) || (is_user_role('hub') && is_post_in_user_hub($initiative_id)))) { ?>
              <?php $published_by_id = (int)get_post_meta( $post->ID, 'last_published_by', true); ?>
              <?php if($published_by_id) { ?>
                <?php $published_user = get_user_by('id', $published_by_id); ?>
                <div class="mt-2 text-sm text-gray-600">
                  <em>Last published by: <?php echo get_user_meta( $published_user->ID, 'nickname', true ); ?> <strong>[<?php echo $published_by_id; ?>]</strong></em>
                </div>
              <?php } ?>
            <?php } ?>
          </div>

          <?php get_template_part('templates/partials/group-info-panel'); ?>
          
          <div class="panel rich-text">
            <?php echo strip_tags(get_field('description', $post), '<p><em><strong><u><ol><ul>< ><blockquote>'); ?>
          </div>


          <?php if(is_user_logged_in() && is_user_role(array('initiative'))) { ?>
            <?php $post_author = (int)$post->post_author; ?>
            <?php if($post_author !== get_current_user_id()) { // TODO: check for co-author access here and across site once plugin is installed ?>
              <?php if(!author_access_is_requested($post->ID)) { ?>
                <form action="" method="post" class="hidden">
                  <button class="btn btn-secondary btn-sm" name="request_post_access" value="<?php echo $post->ID; ?>"><?php echo svg('pencil'); ?>Request edit access</button>
                </form>
              <?php } else { ?>
                <p><strong>Edit access to this group has been requested.</strong></p>
              <?php } ?>
            <?php } ?>
          <?php } ?>

          <?php if(can_write_initiative($post)) { ?>
            <div class="flex flex-wrap gap-2 items-center mb-6">
              <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg(array('edit_post' => get_the_ID()), '/edit-group'); ?>"><?php echo svg('pencil'); ?><?php _e('Edit group', 'tofino'); ?></a>

              <?php if(!is_post_published($post)) { ?>
                <?php get_template_part('templates/buttons/publish-delete', null, array('post_id' => $post->ID)); ?>
              <?php } ?>

              <?php if(get_post_status($post) === 'publish') { ?>
                <?php $confirm_message = __('Are you sure you want to unpublish this group? You can re-publish it from the Dashboard', 'tofino'); ?>
                <form action="" method="post">
                  <button name="unpublish" value="<?php echo (get_the_ID()); ?>" class="btn btn-error btn-sm" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?><?php _e('Unpublish group', 'tofino'); ?></button>
                </form>
              <?php } ?>
            </div>
         <?php } ?>

          <?php if (get_field('email')) { ?>
            <div class="mt-12">
              <h3>Contact <?php the_title(); ?></h3>
              <div id="group-name" class="hidden" data-name="<?php the_title(); ?>"></div>
              <div id="group-email" class="hidden" data-email="<?php echo get_field('email'); ?>"></div>
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
              <p><a class="btn btn-primary btn-sm" href="<?php echo add_query_arg(array('initiative_id' => get_the_ID()), get_the_permalink(422)); ?>"><?php echo svg('plus'); ?><?php _e('Add Healthcheck', 'tofino'); ?></a></p>
            </div>
          <?php } ?>
        </div>
        <div class="w-full lg:w-4/12">
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

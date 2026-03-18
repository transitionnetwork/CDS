<?php $hub_slug = get_user_meta(get_current_user_id(), 'hub', true); ?>
<?php $hub = get_term_by('slug', $hub_slug, 'hub');?>

<?php if(is_user_role(array('super_hub', 'hub'))) { ?>
  <div class="container">
    <div class="alert top alert-info">
      Hub organising is increasingly taking place in our online platform, we'd love it if you <a href="https://hub.transition-space.org/user/registration/by-link?token=QYGDi4kaxLoNi2&spaceId=13" target="_blank">joined us there. </a>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('updated') == 'hub') { ?>
  <div class="container">
    <div class="alert top alert-success">
     <?php _e('Hub details updated.', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('updated') == 'publish') { ?>
  <div class="container">
    <div class="alert top alert-success">
     <?php _e('Group approved and published.', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('updated') == 'trash') { ?>
  <div class="container">
    <div class="alert top alert-success">
     <?php _e('Your group has been deleted.', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('updated') == 'hub_request') { ?>
  <div class="container">
    <div class="alert top alert-success">
     <?php _e('Your access request has been sent to the ' . $hub->name .  ' hub.', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('failed') == 'hub_request') { ?>
  <div class="container">
    <div class="alert top alert-error">
     <?php _e('Cannot request hub access. Your current hub, "' . $hub->name . '", has no super hub users. Please email <a href="mailto:websupport@transitionnetwork.org">websupport@transitionnetwork.org</a> for further information', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php while (have_posts()) : the_post(); ?>
  <?php if (!is_user_logged_in()) { ?>
    <?php wp_redirect(esc_url(add_query_arg('error_code', '1', '/error'))); ?>
  <?php } else { ?>
    <main>
      <div class="container">

        <?php
          // Pre-query trainer posts so we can conditionally show the tab
          $trainer_args = array(
            'author' => get_current_user_id(),
            'post_type' => 'trainers',
          );
          $my_trainer_posts = get_posts($trainer_args);
        ?>

        <div class="flex flex-col lg:flex-row gap-6" id="account-dashboard">
          <!-- Sidebar nav -->
          <nav class="shrink-0 lg:w-56">
            <ul class="dashboard-nav flex flex-row lg:flex-col gap-1 overflow-x-auto lg:overflow-x-visible">
              <li>
                <a href="#nav-account-details" class="dashboard-nav-link active" data-panel="panel-account-details">
                  Account Details
                </a>
              </li>

              <?php if(is_user_role('administrator')) { ?>
                <li>
                  <a href="#nav-reports" class="dashboard-nav-link" data-panel="panel-reports">
                    Reports
                  </a>
                </li>
              <?php } ?>

              <?php if(is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
                <li>
                  <a href="#nav-healthcheck" class="dashboard-nav-link" data-panel="panel-healthcheck">
                    Healthcheck Data
                  </a>
                </li>
              <?php } ?>

              <?php if(is_user_role('administrator') || is_user_role('super_hub') || is_user_role('hub')) { ?>
                <li>
                  <a href="#nav-hub-admin" class="dashboard-nav-link" data-panel="panel-hub-admin">
                    Hub Admin
                  </a>
                </li>
              <?php } ?>

              <li>
                <a href="#nav-initiative-admin" class="dashboard-nav-link" data-panel="panel-initiative-admin">
                  Group Admin
                </a>
              </li>

              <?php if (!empty($my_trainer_posts)) : ?>
                <li>
                  <a href="#nav-trainers" class="dashboard-nav-link" data-panel="panel-trainers">
                    Trainer Profiles
                  </a>
                </li>
              <?php endif; ?>

              <?php if(!is_user_role('initiative')) { ?>
                <li>
                  <a href="#nav-maps" class="dashboard-nav-link" data-panel="panel-maps">
                    Embed Maps
                  </a>
                </li>
              <?php } ?>
            </ul>
          </nav>

          <!-- Content panels -->
          <div class="flex-1 min-w-0">
            <div class="dashboard-panel active" id="panel-account-details">
              <?php get_template_part('/templates/panels/account-details'); ?>
            </div>

            <?php if(is_user_role('administrator')) { ?>
              <div class="dashboard-panel" id="panel-reports">
                <?php get_template_part('/templates/panels/reporting'); ?>
              </div>
            <?php } ?>

            <?php if(is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
              <div class="dashboard-panel" id="panel-healthcheck">
                <?php get_template_part('/templates/panels/healthcheck-data'); ?>
              </div>
            <?php } ?>

            <?php if(is_user_role('administrator') || is_user_role('super_hub') || is_user_role('hub')) { ?>
              <div class="dashboard-panel" id="panel-hub-admin">
                <?php get_template_part('/templates/panels/hub-admin'); ?>
              </div>
            <?php } ?>

            <div class="dashboard-panel" id="panel-initiative-admin">
              <?php if (is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
                <section>
                  <a class="btn btn-primary" href="<?php echo home_url('group-email-deliverability'); ?>">Group email deliverability report</a>
                </section>
              <?php } ?>

              <?php if (is_user_role('administrator') || is_user_role('super_hub')) {
                get_template_part('/templates/panels/initiatives-pending-approval');
              } ?>

              <?php if (is_user_role('hub')) {
                get_template_part('/templates/panels/hub-initiatives-pending-approval');
              } ?>

              <?php get_template_part('/templates/panels/initiatives-created-by-me'); ?>
              <?php get_template_part('/templates/panels/initiatives-co-authored-by-me'); ?>
            </div>

            <?php if (!empty($my_trainer_posts)) : ?>
              <div class="dashboard-panel" id="panel-trainers">
                <?php get_template_part('/templates/panels/trainers'); ?>
              </div>
            <?php endif; ?>

            <?php if(!is_user_role('initiative')) { ?>
              <div class="dashboard-panel" id="panel-maps">
                <?php get_template_part('/templates/panels/maps'); ?>
              </div>
            <?php } ?>
          </div>
        </div>

      </div>
    </main>
  <?php } ?>
<?php endwhile; ?>

<?php $hub_slug = get_user_meta(get_current_user_id(), 'hub', true); ?>
<?php $hub = get_term_by('slug', $hub_slug, 'hub');?>

<?php if(is_user_role(array('super_hub', 'hub'))) { ?>
  <div class="container">
    <div class="alert top alert-info">
      Hub organising is increasingly taking place in our online platform, we’d love it if you <a href="https://hub.transition-space.org/user/registration/by-link?token=QYGDi4kaxLoNi2&spaceId=13" target="_blank">joined us there. </a>
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
    <div class="alert top alert-danger">
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
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>

        <nav class="account-nav">
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a class="nav-item nav-link active" id="nav-account-details-tab" data-toggle="tab" href="#nav-account-details" role="tab" aria-controls="nav-account-details" aria-selected="true">Account Details</a>
            
            <?php if(is_user_role('administrator')) { ?>
              <a class="nav-item nav-link" id="nav-reports-tab" data-toggle="tab" href="#nav-reports" role="tab" aria-controls="nav-reports" aria-selected="false">Reports</a>
            <?php } ?>

            <?php if(is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
              <a class="nav-item nav-link" id="nav-healthcheck-tab" data-toggle="tab" href="#nav-healthcheck" role="tab" aria-controls="nav-healthcheck" aria-selected="false">Healthcheck Data</a>
            <?php } ?>
            
            <?php if(is_user_role('administrator') || is_user_role('super_hub') || is_user_role('hub')) { ?>
              <a class="nav-item nav-link" id="nav-hub-admin-tab" data-toggle="tab" href="#nav-hub-admin" role="tab" aria-controls="nav-hub-admin" aria-selected="false">Hub Admin</a>
            <?php } ?>
            
            <a class="nav-item nav-link" id="nav-initiative-admin-tab" data-toggle="tab" href="#nav-initiative-admin" role="tab" aria-controls="nav-initiative-admin" aria-selected="false">Group Admin</a>

            <a class="nav-item nav-link" id="nav-trainers-tab" data-toggle="tab" href="#nav-trainers" role="tab" aria-controls="nav-trainers" aria-selected="false">My Trainer Profiles</a>
            
            <?php if(!is_user_role('initiative')) { ?>
              <a class="nav-item nav-link" id="nav-maps-tab" data-toggle="tab" href="#nav-maps" role="tab" aria-controls="nav-maps" aria-selected="false">Embed Maps</a>
            <?php } ?>
          </div>
        </nav>
        
        <div class="account-content tab-content" id="nav-tabContent">
          <div class="tab-pane fade show active" id="nav-account-details" role="tabpanel" aria-labelledby="nav-account-details-tab">
            <?php get_template_part('/templates/panels/account-details'); ?>
          </div>
          
          <div class="tab-pane fade" id="nav-reports" role="tabpanel" aria-labelledby="nav-reports-tab">
            <?php if(is_user_role('administrator')) { ?>
              <?php get_template_part('/templates/panels/reporting'); ?>
            <?php } ?>
          </div>
          
          <div class="tab-pane fade" id="nav-healthcheck" role="tabpanel" aria-labelledby="nav-healthcheck-tab">
              <?php if(is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
              <?php get_template_part('/templates/panels/healthcheck-data'); ?>
            <?php } ?>
          </div>
          
          <div class="tab-pane fade" id="nav-hub-admin" role="tabpanel" aria-labelledby="nav-hub-admin-tab">
            <?php if(is_user_role('administrator') || is_user_role('super_hub') || is_user_role('hub')) { ?>
              <?php get_template_part('/templates/panels/hub-admin'); ?>
            <?php } ?>
          </div>
          
          <div class="tab-pane fade" id="nav-initiative-admin" role="tabpanel" aria-labelledby="nav-initiative-admin-tab">
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

          <div class="tab-pane fade" id="nav-trainers" role="tabpanel" aria-labelledby="nav-trainers-tab">
            <?php get_template_part('/templates/panels/trainers'); ?>
          </div>
          
          <div class="tab-pane fade" id="nav-maps" role="tabpanel" aria-labelledby="nav-maps-tab">
            <?php if(!is_user_role('initiative')) {
              get_template_part('/templates/panels/maps');
            } ?>
          </div>
        </div>
      </div>
    </main>
  <?php } ?>
<?php endwhile; ?>

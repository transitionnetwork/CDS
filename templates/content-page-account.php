<?php if(get_query_var('updated') == 'hub') { ?>
  <div class="container">
    <div class="alert top alert-success">
     <?php _e('Hub details updated.'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('updated') == 'publish') { ?>
  <div class="container">
    <div class="alert top alert-success">
     <?php _e('Initiative approved and published.'); ?>
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
        <?php get_template_part('/templates/panels/account-details'); ?>
        
        <?php if(is_user_role('administrator')) { ?>
          <?php get_template_part('/templates/panels/reporting'); ?>
        <?php } ?>
        
        <?php if(is_user_role('administrator')) { ?>
          <?php get_template_part('/templates/panels/healthcheck-data'); ?>
        <?php } ?>
        
        <?php if(is_user_role('administrator') || is_user_role('super_hub') || is_user_role('hub')) { ?>
          <?php get_template_part('/templates/panels/hub-admin'); ?>
        <?php } ?>
        
        <?php get_template_part('/templates/panels/initiatives-created-by-me'); ?>
        
        <?php if (is_user_role('administrator') || is_user_role('super_hub')) {
          get_template_part('/templates/panels/initiatives-pending-approval');
        } ?>

        <?php if (is_user_role('hub')) {
          get_template_part('/templates/panels/hub-initiatives-pending-approval');
        } ?>
        
        <?php if(!is_user_role('initiative')) {
          get_template_part('/templates/panels/maps');
        } ?>
      </div>
    </main>
  <?php } ?>
<?php endwhile; ?>

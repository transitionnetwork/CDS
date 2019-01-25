<?php while (have_posts()) : the_post(); ?>
  <?php if (!is_user_logged_in()) { ?>
    <?php wp_redirect('/member-login'); ?>
  <?php } else { ?>
    <main>
      <div class="container">
        <?php acf_form_head(); ?>
        <h1><?php _e('Dashboard') ?></h1>
        <?php get_template_part('/templates/panels/account-details'); ?>
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

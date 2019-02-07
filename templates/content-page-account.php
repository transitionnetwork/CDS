<?php while (have_posts()) : the_post(); ?>
  <?php if (!is_user_logged_in()) { ?>
    <div class="container">
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php the_content(); ?>
      <ul class="btn-list">
        <li><a href="<?php echo home_url('member-register'); ?>" class="btn btn-primary">Register</a></li>
        <li><a href="<?php echo home_url('member-login'); ?>" class="btn btn-secondary">Sign In</a></li>
      </ul>
      <p></p>
    </div>
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

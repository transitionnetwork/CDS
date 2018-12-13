<?php while (have_posts()) : the_post(); ?>
  <?php if (!is_user_logged_in()) { ?>
    <main class="login">
      <div class="container">
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>
        <?php the_content(); ?>
        <?php wp_login_form(); ?>
        <p>Or <a href="register">Register</a> if you don't have an account</p>
      </div>
    </main>
  <?php } else { ?>
    <main>
      <div class="container">
        <?php acf_form_head(); ?>
        <h1><?php _e('Dashboard') ?></h1>
        <?php get_template_part('/templates/panels/account-details'); ?>
        
        <?php if (is_user_role('administrator') || is_user_role('super_hub')) {
          get_template_part('/templates/panels/initiatives-pending-approval');
        }?>

        <?php if (is_user_role('hub')) {
          get_template_part('/templates/panels/hub-initiatives-pending-approval');
        }?>
        
        <?php if(!is_user_role('administrator')) {
          get_template_part('/templates/panels/initiatives-created-by-me');
        } ?>
          
        <?php if(!is_user_role('administrator')) {
          get_template_part('/templates/panels/initiatives-created-by-others');
        } ?>
        
        <?php get_template_part('/templates/panels/maps'); ?>
      </div>
    </main>
  <?php } ?>
<?php endwhile; ?>

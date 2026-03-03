<div class="container">
  <div class="flex justify-center">
    <div class="w-full max-w-2xl xinc-login">
      <?php while (have_posts()) : the_post(); ?>
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>

        <?php if(is_plugin_active('daggerhart-openid-connect-generic/openid-connect-generic.php') && !is_user_logged_in()) { ?>
          <?php echo do_shortcode('[openid_connect_generic_login_button button_text="Register or Login with your Transition ID"]'); ?> 
        <?php } ?>

      <?php endwhile; ?>
    </div>
  </div>
</div>

<?php
/**
 * Template Name: Xinc Login
 *
 * @package WordPress
 */
?>

<?php get_header(); ?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 col-sm-10 col-md-8 xinc-login">
      <?php while (have_posts()) : the_post(); ?>
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>

        <?php if(is_plugin_active('daggerhart-openid-connect-generic/openid-connect-generic.php') && !is_user_logged_in()) { ?>
          <?php echo do_shortcode('[openid_connect_generic_login_button button_text="Login with your Transition ID"]'); ?> 
        <?php } ?>

        <?php the_content(); ?>
        <?php if(!is_user_logged_in()) {
          echo do_shortcode('[openid_connect_generic_login_button]');
        } ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>

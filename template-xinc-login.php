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

          <p><a class="btn btn-ouline" href="https://id.transition-space.org/realms/cloudron/protocol/openid-connect/auth?response_type=code&amp;scope=email%20profile%20openid%20offline_access&amp;client_id=groups.stage.xinc.digital&amp;state=6ef34fa48044e1eb6b7e48ceb67ef062&amp;redirect_uri=https%3A%2F%2Fgroups.stage.xinc.digital%2Fwp-admin%2Fadmin-ajax.php%3Faction%3Dopenid-connect-authorize">Login with OpenID Connect</a></p>

        <?php the_content(); ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>

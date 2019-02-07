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
        <?php the_content(); ?>
      <?php endwhile; ?>
    </div>
  </div>
</div>
<?php get_footer(); ?>

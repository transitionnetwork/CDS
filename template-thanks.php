<?php
/**
 * Template Name: Thank you for your submission
 */


 get_header(); ?>

<div class="container">
  <h1><?php echo \Tofino\Helpers\title(); ?></h1>
  <?php the_content(); ?>
  <ul>
     <li><a class="btn btn-primary" href="http://cds.dev/add-initiative/">Add another initiative</a></li>
     <li><a class="btn btn-primary" href="http://cds.dev/my-hub-initiatives/">View all my initiatives</a></li>
  </ul>
</div>

<?php get_footer();

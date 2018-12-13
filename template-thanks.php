<?php
/**
 * Template Name: Thank you for your submission
 */


 get_header(); ?>

<?php while (have_posts()) : the_post(); ?>
<div class="container">
  <h1><?php echo \Tofino\Helpers\title(); ?></h1>
  <?php the_content(); ?>
  <ul class="button-group">
    <?php parse_str($_SERVER['QUERY_STRING'], $queries); ?>
    <?php if(array_key_exists('edited_post', $queries)) { ?>
      <li><a class="btn btn-primary" href="<?php echo get_permalink($queries['edited_post']); ?>">View edited initiative</a></li>
    <?php } ?>
    <?php if(array_key_exists('added_post', $queries)) { ?>
      <?php
      $args = array(
        'post_type' => 'initiatives',
        'posts_per_page' => 1,
        'orderby' => 'post_date',
        'order' => 'DESC'
      );

      $posts = get_posts($args);
      $post_url = $posts[0]->guid; ?>
      <li><a class="btn btn-primary" href="<?php echo $post_url ?>">View added initiative</a></li>
    <?php } ?>
    <li><a class="btn btn-primary" href="<?php echo get_permalink(13); ?>">Add another initiative</a></li>
    <li><a class="btn btn-primary" href="<?php echo get_permalink(24); ?>">View all my initiatives</a></li>
     
  </ul>
</div>
<?php endwhile; ?>

<?php get_footer();

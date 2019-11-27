<?php
/**
 * Template Name: Thank you for your submission
 */


 get_header(); ?>

<?php
//NO LONGER IN USE. REPLACED BY ALERTS
?>

<?php while (have_posts()) : the_post(); ?>
<div class="container">
  <h1><?php echo \Tofino\Helpers\title(); ?></h1>
  <?php the_content(); ?>
  <ul class="btn-list">
    <?php if(get_query_var('edited_post')) { ?>
      <li><a class="btn btn-primary" href="<?php echo parse_post_link(get_query_var('edited_post')); ?>">&raquo <?php _e('View edited initiative', 'tofino'); ?></a></li>
    <?php } ?>
    <?php if(get_query_var('added_post')) { ?>
      <?php
      $args = array(
        'post_type' => 'initiatives',
        'posts_per_page' => 1,
        'orderby' => 'post_date',
        'order' => 'DESC',
        'post_status' => array('publish', 'pending')
      );

      $posts = get_posts($args);
      $post_url = $posts[0]->guid; ?>
      <li><a class="btn btn-primary" href="<?php echo $post_url ?>">&raquo <?php _e('View added initiative', 'tofino'); ?></a></li>
    <?php } ?>
    <li><a class="btn btn-primary" href="<?php echo get_permalink(13); ?>">&raquo <?php _e('Add another initiative', 'tofino'); ?></a></li>
  </ul>
</div>
<?php endwhile; ?>

<?php get_footer();

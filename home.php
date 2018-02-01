<?php get_header(); ?>

<main>
  <div class="row justify-content-between">
    <div class="col-12 col-lg-8">
      <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post(); ?>
          <div class="item">
            <h2 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="post-date">Posted On: <span><?php the_date('F jS, Y'); ?></span></div>
            <?php the_content(); ?>
            <?php echo get_the_tag_list('<p>Tags: ',', ','</p>'); ?>
          </div>
        <?php endwhile; ?>
        <?php the_posts_pagination(); ?>
      <?php else :
        get_template_part( 'templates/no-posts');
      endif; ?>
    </div>
    <div class="col-12 col-md-3">
      <aside>
        <?php get_template_part('templates/sidebar'); ?>
      </aside>
    </div>
  </div>
</main>

<?php get_footer(); ?>

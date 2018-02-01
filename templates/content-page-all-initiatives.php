<?php $user_id = get_current_user_id(); ?>

<?php
$args = array(
  'posts_per_page' => -1,
  'post_type' => 'initiatives'
); ?>

<?php $posts = get_posts($args); ?>

<main>
  <div class="container">
    <h1><?php echo \Tofino\Helpers\title(); ?></h1>
    <?php if($posts) : ?>
      <?php foreach($posts as $post) : ?>
        <?php setup_postdata($post); ?>
        <div class="post-summary">
          <div>
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br/>
            owner: <?php echo get_the_author_meta('user_login'); ?>
          </div>
          <div>
            <a class="btn btn-primary btn-sm" href="<?php the_permalink(); ?>">View</a>
            <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' ))) : ?>
              <?php $params = array('edit_post' => get_the_ID()); ?>
              <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit</a>
            <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
    There aren't any initatives yet
    <?php endif; ?>
  </div>
</main>

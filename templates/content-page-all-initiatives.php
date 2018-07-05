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
    <?php if($posts && is_user_logged_in()) :
      include('partials/list-initiatives.php'); 
    else : ?>
    There aren't any initatives yet
    <?php endif; ?>
  </div>
</main>

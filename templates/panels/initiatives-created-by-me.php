<?php $args = array(
  'post_type' => 'initiatives',
  'author' => wp_get_current_user()->ID,
  'posts_per_page' => -1,
  'post_status' => array('publish', 'pending')
);
$posts = get_posts($args); ?>

<section>
  <h2>Initiatives created by me</h2>
  <?php if ($posts) :
    list_initiatives($posts);
  else : ?>
    You haven't added any initiatives yet
  <?php endif; ?>

  <div class="button-block"><a href="/add-initiative" class="btn btn-primary">Add new Initiative</a></div>
</section>

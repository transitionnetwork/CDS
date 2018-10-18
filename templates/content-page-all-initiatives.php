<?php
$page_title = \Tofino\Helpers\title();
$args = array(
  'posts_per_page' => -1,
  'post_type' => 'initiatives'
); ?>

<?php $posts = get_posts($args);
$i = 0; ?>
<ul id="dom-target" style="display:none;">
  <?php foreach ($posts as $post) :
    if ($i == 0) {
      $cheat_load = get_field('map');
    } ?>
    
    <?php $map = get_field('map', get_the_ID(), false); ?>
    <li class="point" data-lat="<?php echo htmlspecialchars($map['center_lat']); ?>" data-lng="<?php echo htmlspecialchars($map['center_lng']); ?>" data-title="<?php echo get_the_title(); ?>" data-link="<?php the_permalink(); ?>"></li>
    <?php $i++; ?>
  <?php endforeach; ?>
</ul>

<div id="iframe_map"></div>

<main>
  <div class="container">
    <h1><?php echo $page_title ?></h1>
    <?php include('partials/list-initiatives.php'); ?>
    <ul class="button-group">
      <li><a class="btn btn-primary" href="<?php echo get_permalink(13); ?>">Add new initiative</a></li>
    </ul>
  </div>
</main>

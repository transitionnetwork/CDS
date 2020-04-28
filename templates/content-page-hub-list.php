<?php

function run_map_query() {
  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => 5,
  );
  
  $data = array();
  
  $posts = get_posts($args);
  
  if($posts) {
    foreach($posts as $key => $initiative) {
      $map = get_field('map', $initiative->ID);
      if(!empty($map['markers'])) {
        $data[$key]['lat'] = $map['lat'];
        $data[$key]['lng'] = $map['lng'];
        $data[$key]['permalink'] = parse_post_link($initiative->ID);
        $data[$key]['title'] = get_the_title($initiative->ID);
      }
    }
  }

  return $data;
}

$filepath = TEMPLATEPATH . '/cache/initiative-cache.txt';

if(!file_exists($filepath)) {
  $data = run_map_query();
  file_put_contents($filepath, json_encode($data));
} else {
  if(filemtime($filepath) < time() - 10 ) {
    //check if cache file is out of date
    $data = run_map_query();
    file_put_contents($filepath, json_encode($data));
  } else {
    $data = json_decode(file_get_contents($filepath), true);
  }
}

var_dump($data);

?>

<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
      <?php $args = array(
        'taxonomy' => 'hub',
        'hide_empty' => true,
        'exclude' => array(285)
      ); ?>
      <?php $hubs = get_terms($args); ?>
      <?php if($hubs) { ?>
        <div class="row">
          <?php foreach ($hubs as $hub) { ?> 
            <?php set_query_var('hub', $hub); ?>
            <?php get_template_part('templates/partials/tile-hub'); ?>
          <?php } ?>
        </div>
      <?php } ?>
    <?php endwhile; ?>
  </div>
</main>

<?php get_header('map'); ?>

<?php while (have_posts()) : the_post();

  $hub_id = get_query_var('hub_id');
  $authors = get_hub_users($hub_id);

  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1,
    'author__in' => $authors
  );

  $posts = get_posts($args);
  $i = 0; ?>
  
  <ul id="dom-target" style="display:none;">
    <?php foreach ($posts as $post) :
      setup_postdata( $post );      
      $map = get_field('map', get_the_ID(), false); ?>
      <li class="point" data-lat="<?php echo htmlspecialchars($map['center_lat']); ?>" data-lng="<?php echo htmlspecialchars($map['center_lng']); ?>" data-title="<?php echo get_the_title(); ?>" data-link="<?php the_permalink(); ?>"></li>
      <?php $i ++; ?>
    <?php endforeach; ?>
    </ul>
<?php endwhile; ?>

<div id="iframe_map"></div>

<?php get_footer('map'); ?>

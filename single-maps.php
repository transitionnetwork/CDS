<?php get_header('map'); ?>

<?php while (have_posts()) : the_post();
  $author_object = get_user_by('id', get_the_author_id());
  $author_hub_name = get_the_terms($author_object, 'hub')[0]->name;
  $author_hub_id = get_the_terms($author_object, 'hub')[0]->term_id;

  $users = get_objects_in_term($author_hub_id, 'hub');

  $args = array(
    'post_type' => 'initiatives',
    'author__in' => $users,
    'posts_per_page' => -1
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

<?php get_header('map'); ?>
<div id="template-url" url="<?php echo get_template_directory_uri(); ?>"></div>

<?php while (have_posts()) : the_post();

  $hub_id = get_query_var('hub_id');
  $authors = get_hub_users($hub_id);

  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1,
    'author__in' => $authors
  );

  $posts = get_posts($args); ?>  
  <ul id="dom-target" style="display:none;">
    <?php foreach ($posts as $post) :
      generate_map($post);
    endforeach; ?>
    </ul>
<?php endwhile; ?>

<div id="iframe_map"></div>

<?php get_footer('map'); ?>

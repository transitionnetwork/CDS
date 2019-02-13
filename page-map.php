<?php get_header('map'); ?>
<div id="template-url" url="<?php echo get_template_directory_uri(); ?>"></div>

<?php while (have_posts()) : the_post();
  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'tax_query' => array(
      array(
        'taxonomy' => 'hub',
        'field' => 'id',
        'terms' => get_query_var('hub_id')
      )
    )
  );

  $post_ids = get_posts($args); ?>
  <ul id="dom-target" style="display:none;">
    <?php foreach ($post_ids as $post_id) {
      echo generate_map($post_id);
    } ?>
  </ul>
<?php endwhile; ?>

<div id="iframe_map"></div>

<?php get_footer('map'); ?>

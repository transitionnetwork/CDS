<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?></h1>
    <?php endwhile; ?>
    <?php
    $per_page = 20;
    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $args = array(
      'post_type' => 'initiatives',
      'fields' => 'ids',
      'paged' => $paged,
      'posts_per_page' => $per_page,
      'meta_key' => 'last_hc_date',
      'orderby' => 'meta_value',
      'order' => 'DESC',
      'posts_per_page' => -1
    );
    
    $init_query = new WP_Query($args);
    set_query_var('init_query', $init_query);
    get_template_part('templates/tables/initiatives');
    ?>

  </div>
</main>

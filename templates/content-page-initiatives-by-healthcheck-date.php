
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
    
    $post_ids = get_posts($args);
    
    $total_pages = ceil(count($post_ids) / $per_page);
    list_initiatives($post_ids); ?>
    
    <nav class="pagination">
      <?php echo paginate_links(array(
      'base' => '%_%',
      'format' => '?paged=%#%',
      'current' => $paged,
      'total' => $total_pages,
      'prev_text' => 'Previous',
      'next_text' => 'Next',
      'type' => 'list',
      )); ?>
    </nav>
  </div>
</main>

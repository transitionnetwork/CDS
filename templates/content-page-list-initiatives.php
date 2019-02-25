<?php

$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'fields' => 'ids',
  'orderby' => 'post_title',
  'order' => 'ASC'
);
if(get_query_var('hub_name')) {
  $args['tax_query'] = array(
    array (
      'taxonomy' => 'hub',
      'field' => 'slug',
      'terms' => get_query_var('hub_name')
    )
  );
}

if(false == get_transient('map_query') || get_query_var('hub_name')) {
  $post_ids = get_posts($args);
  if(!get_query_var('hub_name')) {
    set_transient('map_query', $post_ids, 7 * DAY_IN_SECONDS);
  }
} else {
  $post_ids = get_transient('map_query');
}

$per_page = 20;
$total_pages = ceil(count($post_ids) / $per_page);

delete_transient('map_points');
if(false == get_transient('map_points')) {
  foreach ($post_ids as $post_id) :
    $map_list_items[] = generate_map($post_id);
  endforeach;
  set_transient('map_points', $map_list_items, 7 * DAY_IN_SECONDS);
} else {
  $map_list_items = get_transient('map_points');
} ?>

<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args['paged'] = $paged;
$args['posts_per_page'] = $per_page;
$post_ids = get_posts($args); ?>

<ul id="dom-target" style="display: none;">
  <?php
  foreach($map_list_items as $map_list_item) {
    echo $map_list_item;
  } ?>
</ul>
<div id="iframe_map"></div>

<main>
  <div class="container">
    <?php render_hub_filter(); ?>
    <?php if (get_query_var('hub_name')) :
      $term = get_term_by('slug', get_query_var('hub_name'), 'hub');
    echo '<h1>' . $term->name . '</h1>';
    echo $term->description;
    endif; ?>

    <h1><?php echo $page_title ?></h1>
    <ul class="button-group">
      <?php if(is_user_logged_in()) { ?>
        <li><a class="btn btn-primary" href="<?php echo get_permalink(13); ?>"><?php echo svg('plus'); ?><?php _e('Add New Initiative', 'tofino'); ?></a></li>
        <?php } else { ?>
          <li><a class="btn btn-primary" href="<?php echo get_permalink(460); ?>"><?php echo svg('key'); ?><?php _e('Register to add an initiative', 'tofino'); ?></a></li>
        <?php } ?>
    </ul>
    <?php list_initiatives($post_ids); ?>
    
    <nav class="pagination" aria-label="contact-navigation">
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


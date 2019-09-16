<?php

$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'fields' => 'ids',
  'orderby' => 'post_title',
  'order' => 'ASC'
);

if(get_query_var('hub_name')) {
  $hub_query = array (
    'taxonomy' => 'hub',
    'field' => 'slug',
    'terms' => get_query_var('hub_name')
  );
} else {
  $hub_query = '';
}

if(get_query_var('country')) {
  $country_query =  array (
    'taxonomy' => 'country',
    'field' => 'slug',
    'terms' => get_query_var('country')
  );
} else {
  $country_query = '';
}

if(get_query_var('country') || get_query_var('hub_name')) {
  $args['tax_query'] = array(
    'relation' => 'AND',
    $hub_query,
    $country_query
  );
}

if(get_query_var('search')) { 
  $args['s'] = get_query_var('search');
}

if(false == get_transient('map_query') || (get_query_var('hub_name') && get_query_var('country'))) {
  $post_ids = get_posts($args);
  if(!get_query_var('hub_name') && !get_query_var('country')) {
    set_transient('map_query', $post_ids, 7 * DAY_IN_SECONDS);
  }
} else {
  $post_ids = get_transient('map_query');
}


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
// listed results
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$per_page = 20;

$args['paged'] = $paged;
$args['posts_per_page'] = $per_page;
$initiative_query = new WP_Query($args);
?>

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
      echo '<h1>Hub: ' . $term->name . '</h1>';
      echo $term->description;
    endif; ?>
    
    <?php if (get_query_var('country')) :
      $term = get_term_by('slug', get_query_var('country'), 'country');
      echo '<h1>Country: ' . $term->name . '</h1>';
      echo $term->description;
    endif; ?>

    <h1><?php echo $page_title ?></h1>
    <?php list_initiatives($initiative_query->posts); ?>
    <?php echo render_result_totals($initiative_query); ?>
    
    <nav class="pagination" aria-label="contact-navigation">
      <?php echo paginate_links(array(
        'base' => @add_query_arg('paged', '%#%'),
        'format' => '?paged=%#%',
        'current' => $initiative_query->query['paged'],
        'total' => $wp_query->max_num_pages,
        'prev_text' => 'initiative_query',
        'next_text' => 'Next',
        'type' => 'list',
      )); ?>
    </nav>

    <ul class="button-group">
      <?php if(is_user_logged_in()) { ?>
        <li><a class="btn btn-primary" href="<?php echo get_permalink(13); ?>"><?php echo svg('plus'); ?><?php _e('Add New Initiative', 'tofino'); ?></a></li>
      <?php } else { ?>
        <li><a class="btn btn-primary" href="<?php echo get_permalink(460); ?>"><?php echo svg('key'); ?><?php _e('Register to add an initiative', 'tofino'); ?></a></li>
      <?php } ?>
    </ul>
  </div>
</main>


<?php
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$per_page = 20;

$args = array(
  'post_type' => 'initiatives',
  'fields' => 'ids',
  'orderby' => 'post_title',
  'order' => 'ASC',
  'paged' => $paged,
  'posts_per_page' => $per_page
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

$initiative_query = new WP_Query($args);
?>

<div id="iframe_map" data-hub="<?php echo get_query_var('hub_name'); ?>" data-country="<?php echo get_query_var('country'); ?>" data-search="<?php echo get_query_var('search'); ?>">
  <?php get_template_part('templates/partials/map-panel'); ?>
  <div class="map-loading"><div class="lds-dual-ring"></div></div>
</div>

<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <?php if (!is_user_logged_in() && !empty(get_the_content())) { ?>
        <div class="panel">
          <?php the_content(); ?>
        </div>
      <?php } ?>
    <?php endwhile; ?>
    
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
        'total' => $initiative_query->max_num_pages,
        'prev_text' => 'Prev',
        'next_text' => 'Next',
        'type' => 'list',
      )); ?>
    </nav>

    <ul class="button-group">
      <?php if(is_user_logged_in()) { ?>
        <li><a class="btn btn-primary" href="<?php echo parse_post_link(13); ?>"><?php echo svg('plus'); ?><?php _e('Add New Initiative', 'tofino'); ?></a></li>
      <?php } else { ?>
        <li><a class="btn btn-primary" href="<?php echo parse_post_link(460); ?>"><?php echo svg('key'); ?><?php _e('Register to add an initiative', 'tofino'); ?></a></li>
      <?php } ?>
    </ul>
  </div>
</main>

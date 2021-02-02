<section>
  <?php $hub_users = get_user_meta(wp_get_current_user()->ID, 'hub_user');
  foreach ($hub_users as $hub_user) {
    $terms[] = get_term_by('term_id', $hub_user, 'hub');
  }
    
  foreach($terms as $term) { ?>
    <h2><?php _e('Initiatives pending approval for', 'tofino'); ?> <a href="<?php echo add_query_arg('hub_name', $term->slug, get_post_type_archive_link('initiatives')); ?>"><?php echo $term->name; ?></a></h2>
    <?php
    $args = array(
      'post_type' => 'initiatives',
      'posts_per_page' => -1,
      'post_status' => 'pending',
      'fields' => 'ids',
      'tax_query' => array(
        array(
          'taxonomy' => 'hub',
          'field' => 'slug',
          'terms' => $term->slug
        ),
      )
    );

    $init_query = new WP_Query($args);
    set_query_var('init_query', $init_query);
    get_template_part('templates/tables/initiatives');
    ?>
  <?php } ?>
</section>


<?php

$args = array(
  'post_type' => 'initiatives',
  'author' => wp_get_current_user()->ID,
  'posts_per_page' => -1,
  'post_status' => array('publish', 'pending'),
  'fields' => 'ids'
);

$init_query = new WP_Query($args); ?>

<section>
  <h2><?php _e('Groups created by me', 'tofino'); ?></h2>

  <?php
  if($init_query->have_posts()) { 
    set_query_var('init_query', $init_query);
    get_template_part('templates/tables/initiatives');
  } else {
    _e('You haven\'t added any groups yet', 'tofino');
  }
  ?>

  <div class="button-block"><a href="/add-new-group" class="btn btn-primary btn-sm"><?php echo svg('plus'); ?><?php _e('Add New Group', 'tofino'); ?></a></div>
</section>

<?php if(is_user_role('hub')) { ?>
  <section>
    <?php
    $hub_id = get_field('hub_user', wp_get_current_user());
    $term = get_term_by('term_id', $hub_id, 'hub'); 
    ?>
    <h2><?php _e('Approved groups created by', 'tofino'); ?> <a href="<?php echo add_query_arg('hub_name', $term->slug, get_post_type_archive_link('initiatives')); ?>"><?php echo $term->name; ?></a></h2>

    <?php $args = array(
      'post_type' => 'initiatives',
      'posts_per_page' => -1,
      'post_status' => array('publish'),
      'fields' => 'ids',
      'tax_query' => array(
        array(
          'taxonomy' => 'hub',
          'field' => 'slug',
          'terms' => $term->slug
        ),
      )
    );

    $init_query = new WP_Query($args); ?>
  
    <?php
      if($init_query->have_posts()) { 
        set_query_var('init_query', $init_query);
        get_template_part('templates/tables/initiatives');
      } else {
        _e('You haven\'t added any groups yet', 'tofino');
      }
    ?>
  </section>
<?php } ?>

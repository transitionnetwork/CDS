<section>
    <?php foreach(get_the_terms(wp_get_current_user(), 'hub') as $term) { ?>
      <h2>Initiatives pending approval in <a href="<?php echo get_post_type_archive_link('initiatives') . '?term=' . $term->term_id; ?>"><?php echo $term->name; ?></a></h2>
      <?php
      $args = array(
        'post_type' => 'initiatives',
        'posts_per_page' => -1,
        'post_status' => 'pending',
        'tax_query' => array(
          array(
            'taxonomy' => 'hub',
            'field' => 'slug',
            'terms' => $term->slug
          ),
        )
      );
      $posts = get_posts($args); ?>
    <?php if ($posts) :
      list_initiatives($posts);
    else : ?>
      There aren't any initiatives pending approval for the <?php echo $term; ?> hub.
    <?php endif; ?>
  <?php } ?>
</section>


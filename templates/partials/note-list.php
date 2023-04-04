<?php
$args = array(
  'post_type' => 'initiative_notes',
  // 'meta_query' => array(
  //   array(
  //     'key' => 'initiative_id',
  //     'value' => get_the_ID(),
  //     'compare' => '='
  //   )
  // ),
  'posts_per_page' => -1,
);

$notes_query = new WP_Query($args);

if($notes_query->have_posts()) {
  while ($notes_query->have_posts()) : $notes_query->the_post();
    get_template_part('templates/cards/note-card');
  endwhile;
} else { ?>
  <p>
    <?php _e('There are no notes for this group'); ?>
  </p>
<?php } ?>

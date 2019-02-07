<?php
function get_hub_terms() {
  $term_slugs = wp_cache_get('term_slugs');
  if (false === $term_slugs) {
    $args = array(
      'post_type' => 'initiatives',
      'post_status' => 'publish',
      'posts_per_page' => -1
    );
    $posts = get_posts($args);
    $term_ids = array();
    foreach ($posts as $post) {
      $term_ids[] = get_field('hub_tax', $post->ID);
    }
    $term_ids = array_unique($term_ids);
    foreach ($term_ids as $term_id) {
      $term_slugs[] = get_term_by('id', $term_id, 'hub')->slug;
    }
    sort($term_slugs);
    wp_cache_set('term_slugs', $term_slugs);
  }

  return $term_slugs;
}
function render_hub_filter() {
  //TODO: Cache this filter generation script. Also add counts?
  $term_slugs = get_hub_terms();
?>
  
  <form action="" method="GET" id="hub-filter">
    <?php _e('Filter by hub:'); ?>
    <select name="hub_name" id="term">
      <option value="">All</option>
      <?php foreach ($term_slugs as $slug) { ?>
        <?php if (get_query_var('hub_name') == $slug) {
          $selected = 'selected';
        } else {
          $selected = '';
        } ?>
        <option value="<?php echo $slug; ?>" <?php echo $selected; ?>><?php echo get_term_by('slug', $slug, 'hub')->name; ?></option>
      } ?>
      <?php 
    } ?>
    </select>
    <input type="submit" value="Go">
  </form>
<?php } ?>

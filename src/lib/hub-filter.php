<?php
// function get_hub_terms() {
//   $term_slugs = wp_cache_get('term_slugs');
//   if (false === $term_slugs) {
//     $args = array(
//       'post_type' => 'initiatives',
//       'post_status' => 'publish',
//       'posts_per_page' => -1
//     );
//     $posts = get_posts($args);
//     $term_ids = array();
//     foreach ($posts as $post) {
//       $term_ids[] = get_field('hub_tax', $post->ID);
//     }
//     $term_ids = array_unique($term_ids);
//     foreach ($term_ids as $term_id) {
//       $term_slugs[] = get_term_by('id', $term_id, 'hub')->slug;
//     }
//     sort($term_slugs);
//     wp_cache_set('term_slugs', $term_slugs);
//   }

//   return $term_slugs;
// }

function render_hub_filter() {
  //TODO: Cache this filter generation script. Also add counts?
  $terms = get_terms('hub'); ?>
  <form action="<?php echo home_url('list-initiatives'); ?>" method="GET" id="hub-filter">
    <?php _e('Filter by hub:'); ?>
    <select name="hub_name" id="term" onchange="if(this.value != 0) { this.form.submit(); }">
      <option value="">All</option>
      <?php foreach ($terms as $term) { ?>
        <?php if (get_query_var('hub_name') == $term->slug) {
          $selected = 'selected';
        } else {
          $selected = '';
        } ?>
        <option value="<?php echo $term->slug; ?>" <?php echo $selected; ?>><?php echo $term->name . '&nbsp;(' . $term->count. ')'; ?></option>
      } ?>
      <?php 
    } ?>
    </select>
  </form>
<?php } ?>

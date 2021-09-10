<?php 
function get_status_tag($status) {
  switch($status['value']) {
    case 'forming' :
    case 're-forming' :
    case 'emerging' :
      return 'warning';
      break;
    case 'functioning' :
      return 'primary';
      break;
    case 'unknown' :
    case 'no-hub' :
      return 'secondary';
    default: 
      break;
  }
}

function get_list_terms($taxonomy, $format = null, $post = 0) {
  $post = get_post( $post );
  
  $terms = get_the_terms($post, $taxonomy);

  if($terms && !is_wp_error($terms)) {
    $term_names = [];
    foreach ($terms as $term) {
      $term_names[] = $term->name;
    } 
    return implode(', ', $term_names);
  }

  return;
}

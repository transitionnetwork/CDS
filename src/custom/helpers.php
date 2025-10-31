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

function remove_medialibrary_tab($strings) {
  if ( !current_user_can( 'administrator' ) ) {
    unset($strings["mediaLibraryTitle"]);
  return $strings;
  }
  else
  {
    return $strings;
  }
}
add_filter('media_view_strings','remove_medialibrary_tab');

function restrict_non_Admins(){
  if(!current_user_can('administrator')){
    exit;
  }
}

add_action('wp_ajax_query-attachments','restrict_non_Admins',1);
add_action('wp_ajax_nopriv_query-attachments','restrict_non_Admins',1);


function df_terms_clauses( $clauses, $taxonomy, $args ) {
    //extend get_terms function to accept post_type as an argument
    if ( isset( $args['post_type'] ) && ! empty( $args['post_type'] ) && $args['fields'] !== 'count' ) {
        global $wpdb;

        $post_types = array();

        if ( is_array( $args['post_type'] ) ) {
            foreach ( $args['post_type'] as $cpt ) {
                $post_types[] = "'" . $cpt . "'";
            }
        } else {
            $post_types[] = "'" . $args['post_type'] . "'";
        }

        if ( ! empty( $post_types ) ) {
            $clauses['fields'] = 'DISTINCT ' . str_replace( 'tt.*', 'tt.term_taxonomy_id, tt.taxonomy, tt.description, tt.parent', $clauses['fields'] ) . ', COUNT(p.post_type) AS count';
            $clauses['join'] .= ' LEFT JOIN ' . $wpdb->term_relationships . ' AS r ON r.term_taxonomy_id = tt.term_taxonomy_id LEFT JOIN ' . $wpdb->posts . ' AS p ON p.ID = r.object_id';
            $clauses['where'] .= ' AND (p.post_type IN (' . implode( ',', $post_types ) . ') OR p.post_type IS NULL)';
            $clauses['orderby'] = 'GROUP BY t.term_id ' . $clauses['orderby'];

            //print_r( $clauses );
        } 
    }
    return $clauses;
}

add_filter( 'terms_clauses', 'df_terms_clauses', 10, 3 );


function is_group_in_greylist($post_id) {
  $grey_list = get_field('greylist', 'options');
  $countries = get_the_terms($post_id, 'country');

  if($countries && is_array($grey_list['countries'])) {
    foreach($countries as $country) {
      if(in_array($country->term_id, $grey_list['countries'])) {
        return true;
      }
    }
  }

  return false;
}

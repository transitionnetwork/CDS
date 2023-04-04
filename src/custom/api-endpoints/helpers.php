<?php
function endpoint_format_url($url) {
  $url = trim($url);
  $scheme = parse_url($url, PHP_URL_SCHEME);
  if (empty($scheme)) {
    return 'https://' . ltrim($url, '/');
  }

  return $url;
}

function add_log_message($log_msg) {
  $log_filename = ABSPATH . "api-log.log";

  $log_msg = date('Y-m-d H:i:s') . ' : ' . $log_msg;

  // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
  file_put_contents($log_filename, $log_msg . "\n", FILE_APPEND);
} 

function endpoint_add_custom_routes() {
  register_rest_route( 'cds/v1', '/initiatives', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_groups',
  ));
  
  register_rest_route( 'cds/v1', '/groups', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_groups',
  ));
  
  register_rest_route( 'cds/v1', '/group-distance', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_groups_by_distance',
  ));
  
  register_rest_route( 'cds/v1', '/get_group_by_email', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_group_by_email',
  ));
  
  register_rest_route( 'cds/v1', '/get-groups-murmurations', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_groups_murmurations',
  ));

  register_rest_route('cds/v1', '/get-groups-murmurations/(?P<id>\d+)', array(
    'method' => 'GET', 
    'callback' => 'endpoint_get_groups_murmurations_single', 
  ));
  
  register_rest_route( 'cds/v1', '/trainers', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_trainers',
  ));

  register_rest_route( 'cds/v1', '/hubs', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_hubs',
  ));

  register_rest_route( 'cds/v1', '/groups-full-info', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_groups_full_info',
  ));
  
  register_rest_route( 'cds/v1', '/update-group-baserow', array(
    'methods' => 'POST',
    'callback' => 'endpoint_update_group_baserow',
  ));
}

add_action( 'rest_api_init', 'endpoint_add_custom_routes');

/**
 * Register the /wp-json/acf/v3/posts endpoint so it will be cached.
 */
function wprc_add_acf_posts_endpoint( $allowed_endpoints ) {
  if ( ! isset( $allowed_endpoints[ 'cds/v1' ] ) || ! in_array( 'posts', $allowed_endpoints[ 'cds/v1' ] ) ) {
      $allowed_endpoints[ 'cds/v1' ][] = 'initiatives';
      $allowed_endpoints[ 'cds/v1' ][] = 'groups';
      $allowed_endpoints[ 'cds/v1' ][] = 'group-distance';
      $allowed_endpoints[ 'cds/v1' ][] = 'trainers';
      $allowed_endpoints[ 'cds/v1' ][] = 'hubs';
  }
  return $allowed_endpoints;
}
add_filter( 'wp_rest_cache/allowed_endpoints', 'wprc_add_acf_posts_endpoint', 10, 1);

function endpoint_get_taxonomy_terms($post, $taxonomy) {
  $terms = get_the_terms($post, $taxonomy);
  if($terms) {
    $term_list = array();
    foreach($terms as $term) {
      $term_list[] = $term->name;
    }
    
    return implode(' | ', $term_list);
  } else {
    //return empty string
    return '';
  }
}

function endpoint_get_location($item, $post = true, $clone_field = null) {

  if($clone_field) {
    $map = get_field($clone_field)['map'];
  } else { 
    $map = get_field('map', $item);
  }

  $data = array(
    'address' => get_field('address_line_1', $item),
    'city' => get_field('city', $item),
    'province' => get_field('province', $item),
    'postal_codes' => get_field('postal_codes', $item),
  );

  if($post) {
    $data['country'] = (get_the_terms($post, 'country')) ? get_the_terms($post, 'country')[0]->name : '';
  }

  if($map && !empty($map['markers'])) {
    $data['lat'] = $map['markers'][0]['lat'];
    $data['lng'] = $map['markers'][0]['lng'];
    $data['label'] = $map['markers'][0]['label'];
  }

  return $data;
}

function endpoint_get_contact($post) {
  return array(
    // 'email' => get_field('email', $post),
    'website' => get_field('website', $post),
    'twitter' => get_field('twitter', $post),
    'facebook' => get_field('facebook', $post),
    'instagram' => get_field('instagram', $post),
    'youtube' => get_field('youtube', $post),
    'additional_web_addresses' => (get_field('additional_web_addresses', $post)) ? (get_field('additional_web_addresses', $post)) : ''
  );
}

function endpoint_get_pagination($post_query) {
  // dd($post_query);
  $per_page = (int)$post_query->query['posts_per_page'];

  // $logged_in_status = (is_user_logged_in() ? 'logged_in' : 'not_logged_in');
  // if($logged_in_status === 'logged_in') {
  //   if(is_user_role('administrator')) {
  //     $logged_in_status = 'administrator';
  //   }
  // }

  return array(
    'count' => (int)$post_query->post_count,
    'total' => (int)$post_query->found_posts,
    'per_page' => $per_page,
    'page_number' => (int)($post_query->query_vars['offset'] / $per_page + 1),
    'total_pages' => ceil((int)$post_query->found_posts / $per_page)
  );
}

function endpoint_get_params_args($request) {
  $args = array();
  
  if(!empty($request['per_page'])) {
		if($request['per_page'] > 1500 || $request['per_page'] < 0) {
			$request['per_page'] = 1500;
		}
		
		$args['posts_per_page'] = $request['per_page'];
	}

	if(!empty($request['page'])) {
		$args['offset'] = $args['posts_per_page'] * ($request['page'] - 1);
	} else {
		$args['offset'] = 0;
	}

  $tax_queries = array();
  
  if(!empty($request['hub_name'])) {
		$tax_queries[] = array(
      'taxonomy' => 'hub',
      'field' => 'slug',
      'terms' => $request['hub_name']
    );
	}
  
  if(!empty($request['country'])) {
		$tax_queries[] = array(
      'taxonomy' => 'country',
      'field' => 'slug',
      'terms' => $request['country']
    );
	}

  if(!empty($tax_queries)) {
    $args['tax_query'] = array(
      'operator' => 'OR',
      $tax_queries
    );
  }

  return $args;
}

function get_nearby_locations( $lat, $lng, $distance, $post_type = 'initiatives' ) {
    global $wpdb;
    // Radius of the earth 3959 miles or 6371 kilometers.
    $earth_radius = 3959;

    $sql = $wpdb->prepare( "
        SELECT DISTINCT
            p.ID,
            p.post_title,
            p.post_name,
            map_lat.meta_value as locLat,
            map_lng.meta_value as locLong,
            ( %d * acos(
            cos( radians( %s ) )
            * cos( radians( map_lat.meta_value ) )
            * cos( radians( map_lng.meta_value ) - radians( %s ) )
            + sin( radians( %s ) )
            * sin( radians( map_lat.meta_value ) )
            ) )
            AS distance
        FROM $wpdb->posts p
        INNER JOIN $wpdb->postmeta map_lat ON p.ID = map_lat.post_id
        INNER JOIN $wpdb->postmeta map_lng ON p.ID = map_lng.post_id
        WHERE 1 = 1
        AND p.post_type = '$post_type'
        AND p.post_status = 'publish'
        AND map_lat.meta_key = 'cloned_lat'
        AND map_lng.meta_key = 'cloned_lng'
        HAVING distance < %s
        ORDER BY distance ASC",
        $earth_radius,
        $lat,
        $lng,
        $lat,
        $distance
    );

    // Uncomment and paste into phpMyAdmin to debug.
    // echo $sql;

    $nearbyLocations = $wpdb->get_results( $sql );

    if ( $nearbyLocations ) {
        return $nearbyLocations;
    }
}

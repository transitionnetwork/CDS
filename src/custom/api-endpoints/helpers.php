<?php
function endpoint_add_custom_routes() {
  register_rest_route( 'cds/v1', '/initiatives', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_initiatives',
  ));

  register_rest_route( 'cds/v1', '/trainers', array(
    'methods' => 'GET',
    'callback' => 'endpoint_get_trainers',
  ));
}

add_action( 'rest_api_init', 'endpoint_add_custom_routes');

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

function endpoint_get_location($post) {
  $map = get_field('map', $post);
  $country = (get_the_terms($post, 'country')) ? get_the_terms($post, 'country')[0]->name : '';
  
  $data = array(
    'address' => get_field('address_line_1', $post),
    'city' => get_field('city', $post),
    'province' => get_field('province', $post),
    'postal_codes' => get_field('postal_codes', $post),
    'country' => $country
  );

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
		if($request['per_page'] > 100 || $request['per_page'] < 0) {
			$request['per_page'] = 100;
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

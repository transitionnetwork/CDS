<?php
function get_group_data($post) {
  $logo = get_field('logo', $post);
  $logo = ($logo && $logo['type'] === 'image') ? $logo['sizes']['large'] : '';

  $data = array(
    'id' => $post->ID,
    'title' => $post->post_title,
    'url' => get_the_permalink($post),
    'logo' => $logo,

    'hubs' => endpoint_get_taxonomy_terms($post, 'hub'),
    'countries' => endpoint_get_taxonomy_terms($post, 'country'),
    'topics' => endpoint_get_taxonomy_terms($post, 'topic'),

    'description' => get_field('description', $post),
    
    'location' => endpoint_get_location($post),
    'contact' => endpoint_get_contact($post),
    'last_updated' => get_the_modified_date('Y-m-d H:i:s', $post)
  );
  
  return $data;
}

function get_full_group_data($post) {
  $logo = get_field('logo', $post);
  $logo = ($logo && $logo['type'] === 'image') ? $logo['sizes']['large'] : '';
  $description = strip_tags(get_field('description', $post));

  $data = array(
    'title' => $post->post_title,
    'group_id' => $post->ID,
    'baserow_id' => (int)get_post_meta( $post->ID, 'baserow_id', true ),
    'private_email' => get_field('private_email', $post),
    'user_email' => get_the_author_meta('user_email', $post->post_author),
    'url' => get_the_permalink($post),
    'logo' => $logo,
    'hubs' => endpoint_get_taxonomy_terms($post, 'hub'),
    'countries' => endpoint_get_taxonomy_terms($post, 'country'),
    'topics' => endpoint_get_taxonomy_terms($post, 'topic'),
    'grant_status' => endpoint_get_taxonomy_terms($post, 'grant_status'),
    'description' => str_replace(array("\r", "\n"), '', $description),
    'location_address' => get_field('address_line_1', $post),
    'location_city' => get_field('city', $post),
    'location_province' => get_field('province', $post),
    'location_postcode' => get_field('postal_codes', $post),
    'location_country' => (get_the_terms($post, 'country')) ? get_the_terms($post, 'country')[0]->name : '',

    'contact_website' => get_field('website', $post),
    'contact_twitter' => get_field('twitter', $post),
    'contact_facebook' => get_field('facebook', $post),
    'contact_instagram' => get_field('instagram', $post),
    'contact_youtube' => get_field('youtube', $post),
  );
  
  return $data;
}

function get_groups_from_request($request) {
  $request_attributes = $request->get_attributes();
  
  $data = [];
  $default_per_page = 20;

  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => $default_per_page
  );
  
  if(array_key_exists('slug', $request->get_query_params())) {
    $args['name'] = $request['slug'];
  }

  $params_args = endpoint_get_params_args($request);
  $args = array_merge($args, $params_args);

  $post_query = new WP_Query($args);

  if($post_query->have_posts()) {
    while($post_query->have_posts()) : $post_query->the_post();
      global $post;

      if(isset($request_attributes) && $request_attributes['callback'] === 'endpoint_get_groups_full_info') {
        $data[] = get_full_group_data($post);
      } else {
        $data[] = get_group_data($post);
      }
    endwhile;
  }

  if(!empty($data)) {
    $pagination = endpoint_get_pagination($post_query);
    return array_merge(array('body' => $data), $pagination);
  } else {
    return array(
      'body' => 'No Records Found'
    );
  }
}

function endpoint_get_groups_full_info(WP_REST_Request $request) {
  return get_groups_from_request($request);
}

function endpoint_get_groups(WP_REST_Request $request) {
  return get_groups_from_request($request);
}

function endpoint_get_groups_by_distance($request) {
  $data = [];

  if(!array_key_exists('distance', $request->get_query_params())) {
    //default distance of 50 miles
    $request['distance'] = 50;
  }

  $results = get_nearby_locations($request['lat'], $request['lng'], $request['distance']);

  if($results) {
    foreach($results as $result) {
      $post = get_post($result->ID);
      $data[] = array_merge(get_group_data($post), array('distance_miles' => $result->distance, 'slug' => $result->post_name));
    }
    
    return array_merge(array('body' => $data));
  } else {
    return array(
      'body' => 'No Groups found within ' . $request['distance'] . ' miles of location.'
    );
  }
}

function endpoint_update_group_baserow(WP_REST_Request $request) {

  $response['post_id'] = $request['post_id'];
  $response['baserow_id'] = $request['baserow_id'];

  $response['updated'] = update_post_meta( $request['post_id'], 'baserow_id', $request['baserow_id'] );

  $response = new WP_REST_Response($response);
  $response->set_status(200);

  return ['request' => $response];
}

<?php
function get_group_data($post) {
  $logo = get_field('logo', $post);
  if(is_int($logo)) {
    $logo = wp_get_attachment_image_src( $logo, 'large' );
  } else if (is_array($logo) && array_key_exists('ID', $logo)) {
    $logo = $logo['sizes']['large'];
  } else {
    $logo = '';
  }

  $data = array(
    'id' => $post->ID,
    'title' => $post->post_title,
    'url' => get_the_permalink($post),
    'logo' => $logo,

    'hubs' => endpoint_get_taxonomy_terms($post, 'hub'),
    'countries' => endpoint_get_taxonomy_terms($post, 'country'),

    'description' => strip_tags(get_field('description', $post), '<p><em><strong>'),
    
    'location' => endpoint_get_location($post, null),
    'contact' => endpoint_get_contact($post),
    'last_updated' => get_the_modified_date('Y-m-d H:i:s', $post)
  );

  $tags = get_group_tags($post); 
  $tag_output = array();
  
  if($tags) {
    foreach($tags as $tag) {
      $tag_output[] = $tag['label'];
    }
  }
  
  $data['tags'] = $tag_output;
  
  return $data;
}

function get_full_group_data($post) {
  $logo = get_field('logo', $post);
  $logo = ($logo && $logo['type'] === 'image') ? $logo['sizes']['large'] : '';
  $description = strip_tags(get_field('description', $post), '<p><em><strong>');

  $data = array(
    'title' => $post->post_title,
    'group_id' => $post->ID,
    'baserow_id' => (int)get_post_meta( $post->ID, 'baserow_id', true ),
    'public_email' => get_field('email', $post),
    'user_email' => get_the_author_meta('user_email', $post->post_author),
    'url' => get_the_permalink($post),
    'logo' => $logo,
    'hubs' => endpoint_get_taxonomy_terms($post, 'hub'),
    'countries' => endpoint_get_taxonomy_terms($post, 'country'),
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

  $tags = get_group_tags($post); 
  $tag_output = '';
  
  if($tags) {
    foreach($tags as $tag) {
      $tag_output[] = $tag['label'];
    }
  }
  $data['tags'] = $tag_output;
  
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

function get_group_tags($post) {
  $tags = array(
    array(
     'value' => 'transition-group',
     'label' => 'Transition Group'
    )
  );
  
  $topics = get_field('group_detail_live_projects', $post);
  if($topics) {
    foreach($topics as $item) {
      $tags[] = $item;
    }
  };

  return $tags;
}

function endpoint_get_groups_full_info(WP_REST_Request $request) {
  return get_groups_from_request($request);
}

function endpoint_get_groups(WP_REST_Request $request) {
  return get_groups_from_request($request);
}

function endpoint_get_groups_by_distance(WP_REST_Request $request) {
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

function endpoint_get_group_by_email(WP_REST_Request $request) {
  if(!array_key_exists('email', $request->get_query_params())) {
    return (array(
      'body' => 'Please supply an email address using the email parameter.'
    ));
  }
  
  $data = [];

  $user = get_user_by('email', $request['email']);

  if(!$user) {
    return (array(
      'body' => 'User does not exist.'
    ));
  }

  $args = array(
    'author'        =>  $user->ID,
    'orderby'       =>  'post_date',
    'order'         =>  'ASC',
    'posts_per_page' => -1,
    'post_type' => 'initiatives'
  );

  $post_query = new WP_Query($args);

  if($post_query->have_posts()) {
    while($post_query->have_posts()) : $post_query->the_post();
      global $post;
      $data[] = get_group_data($post);
    endwhile;
  }

  if(!empty($data)) {
    return array('body' => $data);
  } else {
    return array(
      'body' => 'No Records Found'
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

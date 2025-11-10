<?php
function endpoint_get_groups_murmurations_single(WP_REST_Request $request) {
  $args = array(
    'post_type' => 'initiatives',
    'p' => (int)$request['id'],
    'post_status' => 'publish'
  );
  
  $post_query = new WP_Query($args);

  if($post_query->have_posts()) {
    $i = 0;
    while($post_query->have_posts()) : $post_query->the_post();
      global $post;

      $data = get_group_data_murmurations($post);
      $i ++;

    endwhile;
  }

  if(!empty($data)) {
    return $data;
  } else {
    return new WP_Error( 'page_does_not_exist', __('The page you are looking for does not exist'), array( 'status' => 404 ) );
  }
}

function endpoint_get_groups_murmurations(WP_REST_Request $request) {
  $data = [];

  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1
  );
  
  $post_query = new WP_Query($args);

  if($post_query->have_posts()) {
    $i = 0;
    while($post_query->have_posts()) : $post_query->the_post();
      global $post;

      $data[$i] = home_url('/wp-json/cds/v1/get-groups-murmurations/') . $post->ID;
      $i ++;

    endwhile;
  }

  if(!empty($data)) {
    return $data;
  } else {
    return array(
      'body' => 'No Records Found'
    );
  }
}

function get_group_data_murmurations($post) {
  $map = get_field('map');
  $logo = get_logo('logo', $post);

  if(is_int($logo)) {
    $logo = wp_get_attachment_image_src( $logo, 'large' );
  } else if (is_array($logo) && array_key_exists('ID', $logo)) {
    $logo = $logo['sizes']['large'];
  } else {
    $logo = '';
  }

  $link_fields = array('twitter', 'facebook', 'instagram', 'youtube');
  $links = array();
  foreach($link_fields as $link_field) {
    if(get_field($link_field)) {
      $links[] = array(
        'name' => ucwords($link_field),
        'url' => endpoint_format_url(get_field($link_field))
      );
    }
  }

  $additional = get_field('additional_web_addresses');
  if($additional) {
    foreach($additional as $item) {
      if($item['label'] && $item['address']) {
        $links[] = array(
          'name' => $item['label'],
          'url' => endpoint_format_url($item['address'])
        );
      }
    }
  }
  
  $data['linked_schemas'] = array('organizations_schema-v1.0.0');
  $data['name'] = html_entity_decode(get_the_title());
  $data['primary_url'] = (get_field('website')) ? endpoint_format_url(get_field('website')) : get_the_permalink();
  $data['urls'] = $links;

  if(get_field('email')) {
    $data['email'] = strtolower(get_field('email'));
  }
  
  $description = strip_tags(html_entity_decode(get_field('description')));
  $description = trim(preg_replace('/\s\s+/', ' ', $description));

  $data['description'] = $description;
  
  $data['locality'] = get_field('city');
  $data['region'] = get_field('province');
  $data['country_name'] = endpoint_get_taxonomy_terms($post, 'country');

  if($map['markers'] && $map['markers'][0]['lat'] && $map['markers'][0]['lng']) {
    $data['geolocation'] = array(
      'lat' => $map['markers'][0]['lat'],
      'lon' => $map['markers'][0]['lng']
    );
  }

  if($logo && $logo['type'] === 'image') {
    $data['image'] = $logo['sizes']['large'];
  }

  $tags = get_group_tags($post); 
  $tag_output = array();
  
  if($tags) {
    foreach($tags as $tag) {
      $tag_output[] = $tag['label'];
    }
    // $tag_output = implode(', ', $tag_output);
  }

  $data['tags'] = $tag_output;

  $data['metadata'] = array(
    'sources' => array(
      'access_time' => time(),
      'name' => 'Transition Groups',
      'profile_data_url' => get_the_permalink()
    )
  );

  return $data;
}

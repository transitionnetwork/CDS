<?php
function endpoint_get_groups_murmarations_single(WP_REST_Request $request) {
  $args = array(
    'post_type' => 'initiatives',
    'p' => (int)$request['id']
  );
  
  $post_query = new WP_Query($args);

  if($post_query->have_posts()) {
    $i = 0;
    while($post_query->have_posts()) : $post_query->the_post();
      global $post;

      $data = get_group_data_murmarations($post);
      $i ++;

    endwhile;
  }

  if(!empty($data)) {
    return $data;
  } else {
    return array(
      'body' => 'Group not found'
    );
  }
}

function endpoint_get_groups_murmarations(WP_REST_Request $request) {
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

      $data[$i] = get_group_data_murmarations($post);
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

function get_group_data_murmarations($post) {
  $map = get_field('map');
  $logo = get_field('logo');

  $link_fields = array('twitter', 'facebook', 'instagram', 'youtube');
  $links = array();
  foreach($link_fields as $link_field) {
    if(get_field($link_field)) {
      $links[] = array(
        'name' => ucwords($link_field),
        'url' => get_field($link_field)
      );
    }
  }

  $additional = get_field('additional_web_addresses');
  if($additional) {
    foreach($additional as $item) {
      $links[] = array(
        'name' => $item['label'],
        'url' => $item['address']
      );
    }
  }
  
  // if(get_field('email')) {
  //   $links[] = array(
  //     'name' => 'email',
  //     'url' => 'mailto:' . get_field('email')
  //   );
  // }

  $data['linked_schemas'] = array('organizations_schema-v1.0.0');
  $data['name'] = html_entity_decode(get_the_title());
  $data['primary_url'] = (get_field('website')) ? get_field('website') : get_the_permalink();
  $data['urls'] = $links;
  
  $description = strip_tags(html_entity_decode(get_field('description')));
  $description = trim(preg_replace('/\s\s+/', ' ', $description));

  $data['description'] = $description;
  
  $data['locality'] = get_field('city');
  $data['region'] = get_field('province');
  $data['country_name'] = endpoint_get_taxonomy_terms($post, 'country');
  $data['geolocation'] = array(
    'lat' => $map['markers'][0]['lat'],
    'lon' => $map['markers'][0]['lng'],
  );

  if($logo && $logo['type'] === 'image') {
    $data['image'] = $logo['sizes']['large'];
  }

  $tags = array('Transition Group');
  $topics = get_the_terms($post, 'topic');
  if($topics) {
    foreach($topics as $term) {
      $tags[] = html_entity_decode($term->name);
    }
  };

  $data['tags'] = $tags;

  $data['metadata'] = array(
    'sources' => array(
      'access_time' => time(),
      'name' => 'Transition Groups',
      'profile_data_url' => get_the_permalink()
    )
  );

  return $data;
}

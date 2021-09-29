<?php
function endpoint_get_hubs($request) {
  $data = [];
  $default_per_page = 20;
  
  //exclude "no hub" hub
  $args = array(
    'taxonomy' => 'hub',
    'hide_empty' => false,
    'exclude' => array(285)
  );

  $hub_query = new WP_Term_Query($args);

  if($hub_query->get_terms()) {
    foreach($hub_query->get_terms() as $term) {
      $logo = get_field('logo', $term);
      $logo = ($logo) ? $logo['url'] : '';
      
      $data[] = array(
        'title' => $term->name,
        'url' => get_term_link($term),
        'photo' => $logo,
        
        'status' => get_field('status', $term)['label'],
        'description' => get_field('hub_description', $term),
        'email' => get_field('email', $term),

        'location' => endpoint_get_location($term, false),
        'contact' => endpoint_get_contact($term),
      );
    }
  }

  if(!empty($data)) {
    return array(
      'body' => $data,
      'count' => count($data)
    );
  } else {
    return array(
      'body' => 'No Records Found',
    );
  }
}

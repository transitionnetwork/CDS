<?php
// function remove_country_terms_from_trainer_posts() {
//   $args = array(
//     'post_type' => 'trainers',
//     'posts_per_page' => -1,
//     'post_status' => 'any'
//   );

//   $posts = get_posts($args);

//   foreach($posts as $post) {
//     wp_set_post_terms($post->ID, array(), 'country');
//     var_dump('Removed country terms from post ID: ' . $post->ID);
//   }
// }

// remove_country_terms_from_trainer_posts();


function copy_location_to_country_tax() {
  $args = array(
    'post_type' => 'trainers',
    'posts_per_page' => -1,
    'post_status' => 'any'
  );

  $posts = get_posts($args);

  foreach($posts as $post) {
    $location = get_field('general_information_location', $post->ID);
    if(is_array($location) && array_key_exists('map', $location)) {
      
      $markers = ($location['map']['markers']);
      $post_term = get_the_terms($post, 'country');
      
      if($markers && !$post_term) {

        $lng = $markers[0]['lng'];
        $lat = $markers[0]['lat'];
        
        $url = 'https://api.opencagedata.com/geocode/v1/json?q=' . $lat . '%2C+' . $lng . '&key=df2d93044deb4b8e950c8cb028adb508';
        
        $data = file_get_contents($url);
        
        if($data) {
          $data = json_decode($data, true);
          $country_code = $data['results'][0]['components']['country_code'];
          $country_term = get_term_by('slug', strtolower($country_code), 'country');

          if($country_term) {
            wp_set_post_terms($post->ID, array($country_term->term_id), 'country');
            var_dump('Set country term ' . $country_term->name . ' for post ID: ' . $post->ID);
          } else {
            var_dump('No country term found for code: ' . $country_code);
          }
        }
      }
    }
  }
}


copy_location_to_country_tax();

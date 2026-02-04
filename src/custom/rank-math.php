<?php

function get_custom_og_image() {
  if (is_singular('initiatives')) {
    return get_field('logo');
  }
  
  if(is_singular('trainers')) {
    return get_field('general_information_trainer_photo');
  }
  
  return false;
}
/**
 * Hook to manually modify og:tags
 */
add_action( 'rank_math/head', function() {
  if(is_singular('initiatives') || is_singular('trainers')) {
    add_filter( "rank_math/opengraph/facebook/og_image", function($data) {
      $image_field = get_custom_og_image();
      if(is_array($image_field)) {
        return $image_field['url'];
      } else {
        return $data;
      }
    });
    
    add_filter( "rank_math/opengraph/facebook/og_image_secure_url", function($data) {
      $image_field = get_custom_og_image();
      if(is_array($image_field)) {
        return $image_field['url'];
      } else {
        return $data;
      }
    });

    add_filter( "rank_math/opengraph/facebook/og_image_width", function($data) {
      $image_field = get_custom_og_image();
      if(is_array($image_field)) {
        return $image_field['width'];
      } else {
        return $data;
      }
    });
    
    add_filter( "rank_math/opengraph/facebook/og_image_height", function($data) {
      $image_field = get_custom_og_image();
      if(is_array($image_field)) {
        return $image_field['height'];
      } else {
        return $data;
      }
    });
    
    add_filter( "rank_math/opengraph/facebook/og_image_type", function($data) {
      $image_field = get_custom_og_image();
      if(is_array($image_field)) {
        return $image_field['mime_type'];
      } else {
        return $data;
      }
    });
    
    add_filter( "rank_math/opengraph/twitter/twitter_image", function($data) {
      $image_field = get_custom_og_image();
      if(is_array($image_field)) {
        return $image_field['url'];
      } else {
        return $data;
      }
    });
  }
});

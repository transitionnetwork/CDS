<?php
/**
 * Hook to manually modify og:tags
 */
add_action( 'rank_math/head', function() {
  if(is_singular('initiatives')) {
    add_filter( "rank_math/opengraph/facebook/og_image", function($data) {
      if(get_field('logo')) {
        return get_field('logo')['url'];
      } else {
        return $data;
      }
    });
    
    add_filter( "rank_math/opengraph/facebook/og_image_secure_url", function($data) {
      if(get_field('logo')) {
        return get_field('logo')['url'];
      } else {
        return $data;
      }
    });

    add_filter( "rank_math/opengraph/facebook/og_image_width", function($data) {
      if(get_field('logo')) {
        return get_field('logo')['width'];
      } else {
        return $data;
      }
    });
    
    add_filter( "rank_math/opengraph/facebook/og_image_height", function($data) {
      if(get_field('logo')) {
        return get_field('logo')['height'];
      } else {
        return $data;
      }
    });
    
    add_filter( "rank_math/opengraph/facebook/og_image_type", function($data) {
      if(get_field('logo')) {
        return get_field('logo')['mime_type'];
      } else {
        return $data;
      }
    });
    
    add_filter( "rank_math/opengraph/twitter/twitter_image", function($data) {
      if(get_field('logo')) {
        return get_field('logo')['url'];
      } else {
        return $data;
      }
    });
  }
});

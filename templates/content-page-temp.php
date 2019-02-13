<main>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <?php while (have_posts()) : the_post();
        
        $args = array(
          'post_type' => 'initiatives',
          'posts_per_page' => -1,
          'order' => 'ASC'
        );

        $posts = get_posts($args);
        foreach($posts as $post) {
          // var_dump(get_post_meta($post->ID, '_map'));
          // var_dump(get_post_meta($post->ID, 'map'));
          if(get_field('geocoded_address', $post->ID)) {
            $new_address = get_field('geocoded_address', $post->ID);
          } else {
            $new_address = get_field('address_line_1') . ' ' . get_field('city') . ' ' . get_field('province') . ' ' . get_field('postal_code') . ' ' . get_term_by('id', get_field('country'), 'country')->name; 
          }
          
          if(get_field('latitude', $post->ID)) {
            $new_data = array(
              'center_lat' => get_field('latitude', $post->ID),
              'center_lng' => get_field('longitude', $post->ID),
              'zoom' => '14',
              'layers' => array(
                0 => 'OpenStreetMap'
              ),
              'markers' => array (
                0 => array (
                  'default_label' => $new_address,
                  'lat' => (float)get_field('latitude', $post->ID),
                  'lng' => (float)get_field('longitude', $post->ID),
                  'label' => get_term_by('id', get_field('country'), 'country')->name
                )
              )
            );
            $new_data = (serialize($new_data));
            var_dump($new_data);
            update_post_meta($post->ID, '_map', 'field_5bc735b6d792b');
            update_post_meta($post->ID, 'map', $new_data);
            var_dump($post->ID . ' complete');
          } else {
            var_dump($post->ID . ' failed');
          }
        }


        endwhile; ?>
      </div>
    </div>
  </div>
</main>

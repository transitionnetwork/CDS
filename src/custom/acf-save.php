<?php
function acf_custom_save($post_id)
{
  if (get_post_type($post_id) === 'healthchecks') {

    //ensure that hc data is stored in initiative
    update_post_meta( $my_post['post_title'], 'recent_hc', $post_id);
    update_post_meta( $my_post['post_title'], 'last_hc_date', get_the_date('Y-m-d H:i:s', $post_id) );

    $post = get_post($post_id);
    if ($post->post_date === $post->post_modified) { // new post
      custom_email_created_post($post_id, 'healthcheck');
    }
  }
  
  if (get_post_type($post_id) === 'initiatives') {
    $post = get_post($post_id);
    $author = get_userdata($post->post_author);
    
    if ($post->post_date === $post->post_modified) { // new post
      custom_email_created_post($post_id, 'initiative');
    }
    
    //purge transients
    delete_transient('map_query');
    delete_transient('map_points');
    delete_transient('initiative_list_item_', $post_id);
  }

  if(get_post_type($post_id) === 'hub_applications') { // hub_application
    custom_email_hub_application();
  }

  if(in_array(get_post_type($post_id), array('initaitives', 'trainers'))) {
    //clone map latlng
    $map = get_field('map', $post_id);
    if($map && !empty($map['markers'])) {
      update_post_meta( $post_id, 'cloned_lat', $map['markers'][0]['lat']);
      update_post_meta( $post_id, 'cloned_lng', $map['markers'][0]['lng']);
    }
  }
}
add_filter('acf/save_post', 'acf_custom_save', 20);


function acf_custom_after_save($post_id) {
  if(get_post_type($post_id) === 'trainers') { // hub_application
    $name = get_field('general_information_name', $post_id);

    $args = array(
      'ID' => $post_id,
      'post_tite' => $name
    );

    wp_insert_post($args);
  }
}
add_filter('acf/save_post', 'acf_custom_after_save');

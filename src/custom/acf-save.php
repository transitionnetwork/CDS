<?php
function acf_custom_save($post_id)
{
  if (get_post_type($post_id) === 'healthchecks') {

    //ensure that hc data is stored in initiative
    update_post_meta( $my_post['post_title'], 'recent_hc', $post_id);
    update_post_meta( $my_post['post_title'], 'last_hc_date', get_the_date('Y-m-d H:i:s', $post_id) );

    $post = get_post($post_id);
    if ($post->post_date === $post->post_modified) { // new post
      email_created_post($post_id, 'healthcheck');
    }
  }
  
  if (get_post_type($post_id) === 'initiatives') {
    $post = get_post($post_id);
    $author = get_userdata($post->post_author);
    
    if ($post->post_date === $post->post_modified) { // new post
      email_created_post($post_id, 'initiative');
    }
    
    //purge transients
    delete_transient('map_query');
    delete_transient('map_points');
    delete_transient('initiative_list_item_', $post_id);
    
    //update 
  }

  if(get_post_type($post_id) === 'hub_applications') { // hub_application
    email_hub_application();
  }
}
add_filter('acf/save_post', 'acf_custom_save', 20);

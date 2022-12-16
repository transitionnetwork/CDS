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

    //purge cached data directory
    $files = glob(TEMPLATEPATH . '/cache/*'); // get all file names
    foreach($files as $file){ // iterate files
      if(is_file($file)) {
        unlink($file); // delete file
      }
    }
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

  if(get_post_type($post_id) === 'trainers') { // hub_application
    $name = get_field('general_information_name', $post_id);

    $args = array(
      'ID' => $post_id,
      'post_title' => $name
    );

    $created_id = wp_update_post($args);

    //get_flag;
    $flag = get_post_meta($created_id, 'created_flag', true);

    if ($flag === 'already_created') {
      // this is not a new post
      return;
    }
    
    // set flag
    update_post_meta($post_id, 'created_flag', 'already_created');

    $trainer_admin_users = get_users(array( 'role__in' => array( 'trainer_admin')));

    if($trainer_admin_users) {
      $trainer_admin_emails = array();
      foreach($trainer_admin_users as $user) {
        $trainer_admin_emails[] = $user->user_email;
      }
      custom_email_send_transactional_email(7124, $trainer_admin_emails);
    }
  }
}
add_filter('acf/save_post', 'acf_custom_save', 20);

function validate_bio_words( $valid, $value, $field, $input_name ) {

  // Bail early if value is already invalid.
  if( $valid !== true ) {
      return $valid;
  }

  // Prevent value from saving if it contains the companies old name.
  if(str_word_count($value) > 250 ) {
    return __('Please enter less than 250 words.', 'tofino');
  }
  
  return $valid;
}

add_filter('acf/validate_value/name=trainer_bio', 'validate_bio_words', 10, 4);

function validation_group_description( $valid, $value, $field, $input_name ) {

  // Bail early if value is already invalid.
  if( $valid !== true ) {
    return $valid;
  }

  if(strlen($value) < 150 ) {
    return __('Please enter more than 150 characters.' . strlen($value), 'tofino');
  }
  
  return $valid;
}

add_filter('acf/validate_value/name=description', 'validation_group_description', 10, 4);


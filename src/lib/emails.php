<?php
function set_content_type($content_type) {
  return 'text/html';
}
add_filter('wp_mail_content_type', 'set_content_type');

function custom_wp_new_user_notification_email($wp_new_user_notification_email, $user, $blogname)
{
  $key = get_password_reset_key($user);
  $message = '<p>';
  $message .= sprintf(__('Welcome to Transition Initiative ,')) . '</p><p>';
  $message .= 'To set your password, visit the following address:' . '</p><p>';
  $message .= site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . '</p><p>';
  $message .= "Kind regards," . '</p><p>';
  $message .= "The Transition Network Team" . '</p><p>';
  $wp_new_user_notification_email['message'] = $message;
  
  return $wp_new_user_notification_email;
}
add_filter('wp_new_user_notification_email', 'custom_wp_new_user_notification_email', 10, 3);

function send_pending_alert_to_hub($user, $initiatives) {
  $to = array(
    $user->user_email,
    'mark@benewith.com'
  );

  $subject = 'Reminder: You have pending initiatives to approve';

  if ($user->first_name) {
    $message = '<p>Hello ' . $user->first_name . ',</p>';
  } else {
    $message = '<p>Hello ' . $user->display_name . ',</p>';
  }

  $message .= '<p>You have the following initiatives to approve</p>';
  $message .= '<ul>';
  foreach ($initiatives as $initiative) {
    $message .= '<li>' . get_the_title($initiative) . '</li>';
  }
  $message .= '</ul>';

  $message .= '<p>Please login to <a href="' . home_url() . '">Transition Initiative</a> and visit your dashboard to approve or delete these submissions.</p>';

  $message .= '<p>Thanks,<br/>Transition Network</p>';

  if(get_environment() === 'production') {
    wp_mail( $to, $subject, $message);
  } else {
    // wp_mail( 'mark@benewith.com', $subject, $message);
  }
}

function alert_user_initiative_approved($post_id) {
  $author_id = get_post($post_id)->post_author;
  $author = get_user_by('id', $author_id);

  $to = array(
    $author->user_email,
    'mark@benewith.com'
  );

  $subject = 'Your Initiative has now been approved';

  if ($user->first_name) {
    $message = '<p>Hello ' . $user->first_name . ',</p>';
  } else {
    $message = '<p>Hello ' . $user->display_name . ',</p>';
  }

  $message .= '<p>Your Initiative: "<a href="' . parse_post_link($post_id) . '">' . get_the_title($post_id) . '</a>" has now been approved and published.</p>';

  $message .= '<p>Please login to <a href="' . home_url() . '">Transition Initiative</a> and visit your dashboard to make any changes.</p>';

  $message .= '<p>Best Wishes,<br/>Transition Network</p>';

  if(get_environment() === 'production') {
    wp_mail( $to, $subject, $message);
  } else {
    wp_mail( 'mark@benewith.com', $subject, $message);
  }
}


function check_pending_intiatives() {
  // get all hub users
  $args = array(
    'role' => 'hub'
  );

  // The Query
  $user_query = new WP_User_Query( $args );
  $users = $user_query->results;

  foreach ($users as $user) {
    $hub_id = get_user_meta( $user->ID, 'hub_user')[0];

    $args = array(
      'post_type' => 'initiatives',
      'posts_per_page' => -1,
      'post_status' => 'pending',
      'tax_query' => array(
        array(
          'taxonomy' => 'hub',
          'terms' => $hub_id
        )
      )
    );

    $initiatives = get_posts($args);

    if($initiatives) {
      send_pending_alert_to_hub($user, $initiatives);
    }
  }
}

function email_created_post($post_id, $type) {
  if($type == 'healthcheck') {
    //the initiative id is the title of a healthcheck
    $initiative_id = get_the_title($post_id);
  } else {
    $initiative_id = $post_id;
  }
  
  $hub_id = get_field('hub_tax', $initiative_id);
  $hub = get_term_by('id', $hub_id, 'hub');
  
  //get hub users
  $args = array(
    'role__in' => array('hub', 'super_hub'),
    'meta_query' => array(
      array(
        'key' => 'hub_user',
        'value' => $hub_id
      )
    )
  );

  // The Query
  $user_query = new WP_User_Query( $args );
  if($user_query->results) {
    $to = array();
    foreach($user_query->results as $user) {
      $to[] = $user->user_email;
    }
  }
  
  $subject = 'A new ' . $type . ' has been created for the ' . $hub->name . ' hub';
  
  $message = 'Please log into ' . home_url() . ' and browse to your dashboard for further information.';

  if(get_environment() === 'production') {
    wp_mail( $to, $subject, $message);
  } else {
    wp_mail( 'mark@benewith.com', $subject, $message);
  }
  
  return;
}

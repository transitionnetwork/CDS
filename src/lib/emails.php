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

function send_access_request_to_hub($user_id) {
  $hub = get_user_meta($user_id, 'hub', true);
  $hub_users = get_hub_users($hub);

  if($hub_users) {
    $email_addresses = [];
    
    foreach($hub_users as $hub_user) {
      if(is_user_role('super_hub', $hub_user)) {
        $email_addresses[] = $hub_user->user_email;
      }
    }

    if(empty($email_addresses)) {
      //this hub has no super hub users!
      return false;
    }

    //do the email thing and
    $requesting_user = get_user_by('id', $user_id);
    $subject = 'Transition Network: User ' . $requesting_user->display_name . ' has requested hub access';
    $body = '<ul>';
    $body .= '<li>Name: ' . $requesting_user->display_name . '</li>';
    $body .= '<li>Email: ' . $requesting_user->user_email . '</li>';
    $body .= '</ul>';
    $body .= '<p>Please contact the user<p>';
    $body .= '<p>Best Wishes,<br/>Transition Network</p>';

    if(get_environment() === 'production') {
      wp_mail( $email_addresses, $subject, $body);
    } else {
      wp_mail( 'mark@benewith.com', $subject, $message);
    }

    return true;
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

function email_hub_application() {
  $to = 'websupport@transitionnetwork.org';
  $subject = 'A new hub application has been received';
  $message = 'Please login to Transition Iniative to view the request';
  
  if(get_environment() === 'production') {
    wp_mail( $to, $subject, $message);
  } else {
    wp_mail( 'mark@benewith.com', $subject, $message);
  }
}


function email_autologin_reminder_email($user_id) {
  $userdata = get_userdata($user_id);
  $to = $userdata->user_email;
  $subject = 'Transition Town - Are you still the official contact for your Transition group?';
  
  $body = '
  <p>Hello Friends,</p>
  <p>We are emailing you because you are listed as an official contact for a Transition group registered on the Transition Network website.</p>
  <p>Interest in the Transition movement continues to grow and <a href="https://transitionnetwork.org/transition-near-me/">Transition Near Me</a> is one of the most visited pages on our website. We want to help you to make sure that your group’s information is up-to-date, and that Transition Network has the correct contact details for you.</p>
  <p><strong>If your Transition group is still active:</strong></p>
  <ul>
    <li>If this is the correct email address to use as a primary contact for your group, please check the information on the link below.:<br/>
    http://cds.loc/account/?autologin_code=' .get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, true) . '<br/>
    Do make sure that all of your website and social media information is listed correctly - so that people can connect with you.</li>
    <li>If you would like to change the contact details for your Transition group - please reply to this email and cc the person or group to whom it should be changed. If you have a shared email account such as info@yourgroup.org we would advise you to use this address.</li>
  </ul>
  <p><strong>If your Transition group is no longer active:</strong></p>
  <ul><li>To remove your listing from the Transition Network website, please click this link below.<br/>
  http://cds.loc/account#nav-initiative-admin/?autologin_code=' . get_user_meta($user_id, PKG_AUTOLOGIN_USER_META_KEY, true) . '</li></ul>
  <p>If you have any questions - reply to this email and we will do our best to help you.</p>
  <p>Thanks!</p>
  <p>X and the Transition Network team</p>
  ';

  wp_mail( $to, $subject, $body);
}

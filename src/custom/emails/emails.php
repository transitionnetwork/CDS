<?php
function set_content_type($content_type) {
  return 'text/html';
}
add_filter('wp_mail_content_type', 'set_content_type');

function custom_retrieve_password_message( $message, $key, $user_login, $user_data  ) {
  
  $message = '<p>Someone has requested a password reset for your account using the email address ' . sprintf(__('%s'), $user_data->user_email) . '</p>

  <p>If this was a mistake, just ignore this email and nothing will happen.</p>

  <p>To reset your password, please <a href="' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') .  '">click here</a> or visit the following address:</p>

  <p>' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . '</p>';

  return $message;
}
add_filter( 'retrieve_password_message', 'custom_retrieve_password_message', 10, 4 );

function custom_wp_new_user_notification_email($wp_new_user_notification_email, $user, $blogname) {

  $parent_id = get_user_meta( $user->ID, 'parent_id', true);
  $parent_user = get_user_by('id', $parent_id);
  
  $key = get_password_reset_key($user);
  $message = '<p><a href="' . home_url() . '">';
  $message .= sprintf(__('Welcome to Transition Groups,')) . '</a></p>';

  if($parent_id) {
    $message .= '<p>' . $parent_user->user_email  . ' has registered you at our website.' . '</p>';
  }

  $message .= '<p>To set your password, please <a href="' . site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login')  . '">click here</a> or visit the URL below:' . '</p>';
  $message .= '<p>' . site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login') . '</p>';
  $message .= '<p>' . 'Kind regards,' . '</p>';
  $message .= '<p>' . 'The Transition Network Team' . '</p>';
  $wp_new_user_notification_email['message'] = $message;

  $wp_new_user_notification_email['headers'] = array('Content-Type: text/html; charset=UTF-8');

  return $wp_new_user_notification_email;
}
add_filter('wp_new_user_notification_email', 'custom_wp_new_user_notification_email', 10, 3);

function custom_email_send_pending_alert_to_hub($user, $initiatives) {
  $to = array(
    $user->user_email,
  );

  $subject = 'Reminder: You have pending groups to approve';

  if ($user->first_name) {
    $message = '<p>Hello ' . $user->first_name . ',</p>';
  } else {
    $message = '<p>Hello ' . $user->display_name . ',</p>';
  }

  $message .= '<p>Brilliant news! You have a new group in your area.</p>';
  $message .= '<p>They are relying on you to make them visible to the world.</p>';

  $message .= '<ul>';
  foreach ($initiatives as $initiative) {
    $message .= '<li>' . get_the_title($initiative) . '</li>';
  }
  $message .= '</ul>';

  $message .= '<p>Please login to <a href="' . home_url() . '">Transition Groups</a> and visit your dashboard to let them be seen.</p>';

  $message .= '<p>Thanks,<br/>Transition Network</p>';

  if (get_environment() === 'production') {
    //dont send cron mail in dev environments
    wp_mail( $to, $subject, $message);
  }
}

function custom_email_send_access_request_to_hub($user_id) {
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

    wp_mail( $email_addresses, $subject, $body);

    return true;
  }
}

function custom_email_alert_user_initiative_approved($post_id) {
  $author_id = get_post($post_id)->post_author;
  $author = get_user_by('id', $author_id);

  $email_post_id = 6607; //default international group

  $welcome_email_map = array(
    'es' => 7436,
    'us' => 8524,
    'de' => 7434,
    'fr' => 7433,
    'be' => 7435,
    'gb' => 7381,
    'scotland' => 7381,
    'ie' => 7381
  );

  $countries = get_the_terms($post_id, 'country');
  if($countries) {
    foreach($countries as $country) {
      //check for custom emails
      if(array_key_exists($country->slug, $welcome_email_map) && get_post_status($welcome_email_map[$country->slug]) === 'publish') {
        $email_post_id = $welcome_email_map[$country->slug];
      }
    }
  }

  $to = array(
    $author->user_email,
  );

  $subject = get_field('subject', $email_post_id);

  if ($author->first_name) {
    $message = '<p>Hi ' . $author->first_name . ',</p>';
  } else {
    $message = '<p>Hi ' . $author->display_name . ',</p>';
  }

  $page = get_post($email_post_id);
  $message .= apply_filters('the_content', $page->post_content);

  wp_mail( $to, $subject, $message);
}

function custom_email_initaitive_author_updated($post_id) {
  $author_id = get_post($post_id)->post_author;
  $author = get_user_by('id', $author_id);

  $to = array(
    $author->user_email,
  );

  $subject = 'You have been made the author of ' . get_the_title($post_id) .  ' group';

  if ($author->first_name) {
    $message = '<p>Hi ' . $author->first_name . ',</p>';
  } else {
    $message = '<p>Hi ' . $author->display_name . ',</p>';
  }

  $message .= 'Please sign into <a href="' . home_url() . '">' . get_bloginfo('name') . '</a> and browse to your dashboard for further information.';

  wp_mail( $to, $subject, $message);
}

function custom_email_created_post($post_id, $type) {
  if($type == 'healthcheck') {
    //the initiative id is the title of a related healthcheck
    $initiative_id = get_the_title($post_id);
  } else {
    $initiative_id = $post_id;
  }
  
  //EMAIL HUB USER
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
  $to = array();
  
  $user_query = new WP_User_Query( $args );
  if($user_query->results) {
    foreach($user_query->results as $user) {
      $to[] = $user->user_email;
    }
  }

  if(is_group_in_greylist($initiative_id)) {
    $greylist_email = get_field('greylist_greylist_admin_email', 'options');
    if($greylist_email) {
      $to[] = $greylist_email;
    }
  }
  
  $subject = 'A new ' . $type . ' has been created for the ' . $hub->name . ' hub';
  
  $message = 'Please sign into <a href="' . home_url('/account#nav-initiative-admin/') . '">' . get_bloginfo('name') . ' to manage your groups.</a>';

  wp_mail( $to, $subject, $message);

  // //EMAIL USER
  $author_id = get_post($post_id)->post_author;
  $author = get_user_by('id', $author_id);

  $to = array(
    $author->user_email,
  );

  $email_post_id = 6608;

  $subject = get_field('subject', $email_post_id);

  if ($author->first_name) {
    $message = '<p>Hi ' . $author->first_name . ',</p>';
  } else {
    $message = '<p>Hi ' . $author->display_name . ',</p>';
  }

  $page = get_post($email_post_id);
  $message .= apply_filters('the_content', $page->post_content);

  wp_mail( $to, $subject, $message);
  
  return;
}

function custom_email_hub_application() {
  $to = 'websupport@transitionnetwork.org';
  $subject = 'A new hub application has been received';
  $message = 'Please login to Transition Iniative to view the request';
  
  wp_mail( $to, $subject, $message);
}

function check_pending_groups() {
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

    $groups = get_posts($args);

    if($groups) {
      custom_email_send_pending_alert_to_hub($user, $groups);
    }
  }
}

function filter_dev_emails($args) {
  if(get_environment() === 'production') {
  } else {
    $args['to'] = 'mark@benewith.com';
  } 
  return $args;
}
add_filter( 'wp_mail', 'filter_dev_emails');

function custom_email_send_transactional_email($email_id, $email_addresses, $language = null) {
  $subject = get_field('subject', $email_id);
  $message = get_post_field('post_content', $email_id);

  wp_mail($email_addresses, $subject, $message);
}

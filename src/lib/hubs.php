<?php 
//hub_access
function request_hub_access($hub_term_id) {
  update_user_meta(get_current_user_id(), 'hub_admin_requested', $hub_term_id);
  
  //fire off email to admin and super hub advising them of request
  $args = array(
    'role__in' => array('administrator', ''),
    'number' => -1
  );

  $users = get_users( $args );

  if($users) {
    $user_emails = array();
    foreach($users as $user) {
      $user_emails[] = $user->user_email;
    }
  
    custom_email_send_transactional_email(8602, $user_emails);
  }
}

function delete_hub_access_request($user_id) {
  delete_user_meta((int)$user_id, 'hub_admin_requested');
}

function grant_hub_access($user_id) {
  //Change user perms from initiative to hub
  $user = new WP_User($user_id);
  $user->remove_role('initiative');
  $user->add_role('hub');

  $hub_id = get_user_meta($user_id, 'hub_admin_requested', true);

  //link user account to hub
  update_field('hub_user', $hub_id, 'user_' . $user_id);

  //delete_hub_request
  delete_user_meta((int)$user_id, 'hub_admin_requested');

  //fire off confirmation email to user
  custom_email_send_transactional_email(8603, $user->user_email);

}

function is_hub_access_requested($hub_term_id) {
  if((int)get_user_meta(get_current_user_id(), 'hub_admin_requested', true) === $hub_term_id) {
    return TRUE;
  }
  
  return FALSE;
}


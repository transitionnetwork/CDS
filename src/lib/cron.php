<?php
//Cron
function add_cron_intervals($schedules) {
  $schedules['weekly'] = array(
    'interval' => 604800,
    'display' => esc_html__('Weekly')
  );

  $schedules['four_days'] = array(
    'interval' => 345600,
    'display' => esc_html__('Every 4 days')
  );
  
  return $schedules;
}
add_filter('cron_schedules', 'add_cron_intervals');

//trigger email reminder for unapproved groups
if (!wp_next_scheduled('email_pending_groups_hook')) {
  wp_schedule_event(time(), 'four_days', 'email_pending_groups_hook');
}

add_action('email_pending_groups_hook', 'check_pending_groups');
//

//check for last healthcheck submission
if (!wp_next_scheduled('email_old_healthchecks_hook')) {
  wp_schedule_event(time(), 'four_days', 'email_old_healthchecks_hook');
}

add_action('email_old_healthchecks_hook', 'check_old_healthcheck');
//

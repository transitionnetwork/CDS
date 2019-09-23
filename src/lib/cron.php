<?php
//Cron
function add_cron_intervals($schedules)
{
  $schedules['weekly'] = array(
    'interval' => 604800,
    'display' => esc_html__('Weekly'),
  );
  
  return $schedules;
}
add_filter('cron_schedules', 'add_cron_intervals');

if (!wp_next_scheduled('email_auto_send_hook')) {
  wp_schedule_event(time(), 'weekly', 'email_auto_send_hook');
}

// add_action('email_auto_send_hook', 'check_pending_intiatives');

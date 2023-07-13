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

//check for inactive authors
// if (!wp_next_scheduled('email_inactive_authors')) {
//   wp_schedule_event(time(), 'four_days', 'email_inactive_authors');
// }

// add_action('email_inactive_authors', 'check_inactive_authors');
//

function email_inactive_authors() {
  $date = new DateTime("-1 year");

  $args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1,
    'fields' => 'ids',
    'tax_query' => array(
      array(
        'taxonomy' => 'hub',
        'term_id' => 'term_id',
        'terms' => array(800)
      )
    ),
    'meta_query' => array(
      'relation' => 'OR',
      array(
        'key' => 'author_last_logged_in',
        'value' => $date->format('Y-m-d H:i:s'),
        'compare' => '<',
        'type' => 'DATE'
      ),
      array(
        'key' => 'author_last_logged_in',
        'compare' => 'NOT EXISTS'
      )
    )
  );

  $post_ids = get_posts($args);

  $results = array();
  
  foreach($post_ids as $post_id) {
    custom_email_autologin_reminder_email($post_id);
  }
}

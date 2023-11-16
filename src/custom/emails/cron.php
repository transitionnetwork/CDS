<?php
//Cron
function add_cron_intervals($schedules) {
  $schedules['weekly'] = array(
    'interval' => 86400 * 7,
    'display' => esc_html__('Weekly')
  );

  $schedules['four_days'] = array(
    'interval' => 86400 * 4,
    'display' => esc_html__('Every 4 days')
  );

  $schedules['eight_days'] = array(
    'interval' => 86400 * 8,
    'display' => esc_html__('Every 8 days')
  );
  
  return $schedules;
}
add_filter('cron_schedules', 'add_cron_intervals');

//trigger email reminder for unapproved groups
if (!wp_next_scheduled('email_pending_groups_hook')) {
  wp_schedule_event(time(), 'eight_days', 'email_pending_groups_hook');
}
add_action('email_pending_groups_hook', 'check_pending_groups');

///////////////////////////////////////////////////////////////
//check for inactive authors
if (!wp_next_scheduled('email_inactive_authors_hook')) {
  wp_schedule_event(time(), 'daily', 'email_inactive_authors_hook');
}

add_action('email_inactive_authors_hook', 'email_inactive_authors');

function email_inactive_authors() {
  $days_since_author_login = (get_field('inactive_authors_days_since_author_login', 'options')) ? get_field('inactive_authors_days_since_author_login', 'options') . ' days' : '1 year';
  $last_login = new DateTime('-' . $days_since_author_login);

  $days_email_frequency = (get_field('inactive_authors_days_resend', 'options')) ? get_field('inactive_authors_days_resend', 'options') . ' days' : '1 year';
  $last_email_sent = new DateTime('-' . $days_email_frequency);
  
  $selected_hubs = get_field('reminder_email_hubs', 'options');

  if($selected_hubs) {
    $args = array(
      'post_type' => 'initiatives',
      'posts_per_page' => -1,
      'fields' => 'ids',
      'tax_query' => array(
        array(
          'taxonomy' => 'hub',
          'term_id' => 'term_id',
          'terms' => $selected_hubs
        )
      ),
      'meta_query' => array(
        'relation' => 'AND',
        array(
          'relation' => 'OR',
          array(
            'key' => 'author_last_logged_in',
            'value' => $last_login->format('Y-m-d H:i:s'),
            'compare' => '<',
            'type' => 'DATE'
          ),
          array(
            'key' => 'author_last_logged_in',
            'compare' => 'NOT EXISTS'
          )
        ),
        array(
          'relation' => 'OR',
          array(
            'key' => 'last_mail_date',
            'value' => $last_email_sent->format('Y-m-d H:i:s'),
            'compare' => '<',
            'type' => 'DATE'
          ),
          array(
            'key' => 'last_mail_date',
            'compare' => 'NOT EXISTS'
          )
        )
      )
    );
  
    $post_ids = get_posts($args);
  
    foreach($post_ids as $post_id) {
      custom_email_autologin_reminder_email($post_id);
    }
  }
}

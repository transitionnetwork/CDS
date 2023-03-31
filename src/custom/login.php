<?php
function add_last_login_time( $user_login, $user ) {
    // your code
    update_user_meta($user->ID, 'last_logged_in', date('Y-m-d H:i:s'));
}
add_action('wp_login', 'add_last_login_time', 10, 2);
?>

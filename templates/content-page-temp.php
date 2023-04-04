<?php
// batch process last logged in and copy to posts
// $args = array(
//   'fields' => 'ids'
// );

// $user_ids = get_users($args);

// if($user_ids) {
//   foreach($user_ids as $user_id) {
//     $args = array (
//       'post_type' => 'initiatives',
//       'author' => $user_id,
//       'posts_per_page' => -1,
//       'post_status' => array('publish', 'pending', 'draft'),
//       'fields' => 'ids'
//     );

//     $group_ids = get_posts($args);

//     if($group_ids) {
//       foreach($group_ids as $group_id) {
//         $user_last_logged_in = get_user_meta( $user_id, 'last_logged_in', true);
//         if($user_last_logged_in) {
//           update_post_meta($group_id, 'author_last_logged_in', $user_last_logged_in);
//         }
//       }
//     }
//   }
// }

<?php
// function post_groups_to_murmaration() {
//   //let's batch these at 10 at a time
//   $args = array(
//     'post_type' => 'initiatives',
//     'posts_per_page' => -1,
//     'fields' => 'ids'
//   );

//   $post_ids = get_posts($args);
//   if($post_ids) {
//     foreach($post_ids as $post_id) {
//       $body = array(
//         'profile_url' => 'https://transitiongroups.org/wp-json/cds/v1/get-groups-murmurations/' . $post_id
//       );

//       $response = wp_remote_post('https://test-index.murmurations.network/v2/nodes-sync', array(
//         'method' => 'POST',
//         'body' => json_encode($body),
//         'headers' => [
// 		      'Content-Type' => 'application/json',
// 	      ],
//       ))['body'];

//       $response = json_decode($response);
      
//       if($response->errors) {
//         $errors = $response->errors;
//         foreach($errors as $error) {
//           update_post_meta($post_id, 'murmurations_error', json_encode($error));
//         }
//       } else {
//         $node_id = $response->data->node_id;
//         $status = $response->data->status;
        
//         update_post_meta($post_id, 'murmurations_node_id', $node_id);
//         update_post_meta($post_id, 'murmurations_status', $status);
//         update_post_meta($post_id, 'murmurations_error', null);
//       }

      
//       var_dump($response);
//     }

//     var_dump('success');
//   }
// }

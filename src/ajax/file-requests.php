<?php
function increment_download() {
  if (!isset($_POST['value'])) {
    $data['success'] = false;
    echo json_encode($_POST);
    die();
  }

  $file_id = $_POST['value']['file_id'];
  $old_count = $_POST['value']['download_count'];
  $new_count = $old_count + 1;

  update_field('download_count', $new_count, $file_id);

  $data['new_count'] = $new_count;
  
  echo json_encode($data);
  wp_die();
}

add_action('wp_ajax_nopriv_incrementDownload', 'increment_download');
add_action('wp_ajax_incrementDownload', 'increment_download');

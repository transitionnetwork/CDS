<?php

function xinc_drobox_get_token() {
  $key = get_field('dropbox_access_key', 'option');
  $secret = get_field('dropbox_access_secret', 'option');
  $refresh_token = get_field('dropbox_refresh_token', 'option');

  if($key && $secret && $refresh_token) {
    $arr = [];
    
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.dropbox.com/oauth2/token');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=refresh_token&refresh_token=" . $refresh_token);
    curl_setopt($ch, CURLOPT_USERPWD, $key . ':' . $secret);
    
    $headers = array();
    $headers[] = 'Content-Type: application/x-www-form-urlencoded';
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $result = curl_exec($ch);
    $result_arr = json_decode($result, true);
  
    if (curl_errno($ch)) {
      $arr = ['status'=>'error','token'=>null];
    } else if(isset($result_arr['access_token'])) {
      $arr = ['status'=>'ok','token'=>$result_arr['access_token']];
    } else {
      $arr = ['status'=>'error','token'=>null];
    }
    
    curl_close($ch);
    return $arr['token'];
  }
    
  return false;
}

function xinc_dropbox_upload(int $image_id) {

  $token = xinc_drobox_get_token();
  $path = get_attached_file($image_id);

  if($token && $path) {
    $fp = fopen($path, 'rb');
    $size = filesize($path);

    $cheaders = array('Authorization: Bearer ' . $token,
        'Content-Type: application/octet-stream',
        'Dropbox-API-Arg: {"path":"'.$path.'", "mode":"add"}');
  
    $ch = curl_init('https://content.dropboxapi.com/2/files/upload');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $cheaders);
    curl_setopt($ch, CURLOPT_PUT, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_INFILE, $fp);
    curl_setopt($ch, CURLOPT_INFILESIZE, $size);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    
    curl_close($ch);
    fclose($fp);
    
    // echo $response;
  }

  wp_delete_attachment( $image_id, true );
}

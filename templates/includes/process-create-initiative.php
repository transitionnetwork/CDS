<?php
if (isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {
  $hasError = false;
  $postSuccess = false;

  $title = sanitize_text_field($_POST['title']);
  $description = trim(wp_strip_all_tags($_POST['description']));
  $healthcheck = trim(wp_strip_all_tags($_POST['healthcheck']));

  $address = sanitize_text_field($_POST['address-line-1']);
  $city = sanitize_text_field($_POST['city']);
  $province = sanitize_text_field($_POST['province']);
  $postcode = sanitize_text_field($_POST['postcode']);

  $latitude = sanitize_text_field($_POST['latitude']);
  $longitude = sanitize_text_field($_POST['longitude']);
  $geocodedAddress = sanitize_text_field($_POST['geocoded-address']);

  $email = sanitize_email($_POST['email']);
  $website = sanitize_text_field($_POST['website']);
  $twitter = sanitize_text_field($_POST['twitter']);
  $facebook = sanitize_text_field($_POST['facebook']);
  $youtube = sanitize_text_field($_POST['youtube']);
  $instagram = sanitize_text_field($_POST['instagram']);

  if ($website != "" && $ret = parse_url($website)) {
    if (!isset($ret["scheme"])) {
      $website = "http://{$website}";
    }
  }

  if ($twitter != "" && $ret = parse_url($twitter)) {
    if (!isset($ret["scheme"])) {
      $twitter = "http://{$twitter}";
    }
  }

  if ($facebook != "" && $ret = parse_url($facebook)) {
    if (!isset($ret["scheme"])) {
      $facebook = "http://{$facebook}";
    }
  }

  if ($youtube != "" && $ret = parse_url($youtube)) {
    if (!isset($ret["scheme"])) {
      $youtube = "http://{$youtube}";
    }
  }

  if ($instagram != "" && $ret = parse_url($instagram)) {
    if (!isset($ret["scheme"])) {
      $instagram = "http://{$instagram}";
    }
  }

  $country = sanitize_text_field($_POST['country']);
  $postedTopics = $_POST['topics'];

  $user = wp_get_current_user();

  if ($title === '') {
    $titleError = true;
    $hasError = true;
  }

  if ($description === '') {
    $descriptionError = true;
    $hasError = true;
  }

  if ($address === '') {
    $addressError = true;
    $hasError = true;
  }

  if ($city === '') {
    $cityError = true;
    $hasError = true;
  }

  if ($country === '') {
    $countryError = true;
    $hasError = true;
  }

  if ($latitude == '') {
    $mapError = true;
    $hasError = true;
  }

  if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $emailError = true;
    $hasError = true;
  }

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $verify_url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $json_response = curl_exec($ch);
  curl_close($ch);

  if (!empty($json_response) && $result = json_decode($json_response, true)) {
    if (isset($result['success']) && $result['success'] == true) {

    } else {
      $captchaError = true;
      $hasError = true;
    }
  }

  //if no errors create initiative
  if (!$hasError) {
    $post_information = array(
      'post_title' => $title,
      'post_type' => 'initiatives',
      'post_status' => 'pending',
      'post_author' => $user->ID,
      'post_content' => $description
    );

    $initiativeID = wp_insert_post($post_information);

    if ($initiativeID) {

      if ($healthcheck != "") {
        $healthcheck = str_replace('/', '-', $healthcheck);
        update_field('healthcheck_last_complete_date', date('Ymd', strtotime($healthcheck)), $initiativeID);
      }

      update_field('address_line_1', $address, $initiativeID);
      update_field('city', $city, $initiativeID);
      update_field('province', $province, $initiativeID);
      update_field('postal_code', $postcode, $initiativeID);
      update_field('geocoded_address', $geocodedAddress, $initiativeID);
      update_field('latitude', $latitude, $initiativeID);
      update_field('longitude', $longitude, $initiativeID);

      update_field('email', $email, $initiativeID);
      update_field('website', $website, $initiativeID);
      update_field('twitter', $twitter, $initiativeID);
      update_field('facebook', $facebook, $initiativeID);
      update_field('youtube', $youtube, $initiativeID);
      update_field('instagram', $instagram, $initiativeID);

      $attachmentid = attachLogo();
      update_field('logo', $attachmentid, $initiativeID);

      update_field('gdpr_consent', 1, $initiativeID);

      if ($postedTopics) {
        $theTopics = array();
        foreach ($postedTopics as $topic) {
          array_push($theTopics, intval($topic));
        }
        wp_set_object_terms($initiativeID, $theTopics, 'topic', false);
      }

      if ($country) {
        $theCountry = array($country);
        wp_set_object_terms($initiativeID, $theCountry, 'country', false);
      }
    }

    // $body = "<p>A new initiative has been created. Click <a href='" . site_url() . "/wp-admin/post.php?post=" . $initiativeID . "&action=edit'>here</a> to review</p>";
    // $adminemail = get_field('initiatives_admin_email', 'options');
    // $emailTo = $adminemail;
    // $headers[] = 'From: ' . $user->user_nicename . ' <' . $user->user_email . '>' . "\r\n";
    // $headers[] = "Content-Type: text/html; charset=UTF-8";
    // $subject = "News Initiative Created";
    // $mailsent = wp_mail($emailTo, $subject, $body, $headers);

    $thanksPage = get_permalink(45);
    wp_redirect($thanksPage);
    exit;
  }
}
?>

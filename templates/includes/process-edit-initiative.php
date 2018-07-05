<?php
if (isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

  $hasError = false;
  $postSuccess = false;

  //first lets grab the object the user is editing
  $editid = intval($_POST['editid']);

  //then the author id of this post...
  $post_author_id = get_post_field('post_author', $editid);

  //...if they don't match you are not allowed to edit
  if (intval(wp_get_current_user()->ID) !== intval($post_author_id)) {
    $hasError = true;
    $updateError = true;
    $updateErrorText = "Oops. Something when wrong, and your initiative has not been updated. Please return to the dashboard and try again.";
  } else {
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
  }

    //if no errors create initiative
  if (!$hasError) {
    $post_information = array(
      'ID' => $editid,
            //include this to auto update slug
      'post_name' => '',
      'post_title' => $title,
      'post_content' => $description
    );

    $initiativeID = wp_update_post($post_information);

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

            //only replace image if they have chosen a new one
      if ($_FILES['uploaded_logo']['error'] == 0) {
        echo "there is an image";
        $attachmentid = attachLogo();
        update_field('logo', $attachmentid, $initiativeID);
      }

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

    $thanksPage = get_permalink(45);
    wp_redirect($thanksPage);
    exit;
  }
}

?>

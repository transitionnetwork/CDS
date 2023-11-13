<?php
function xinc_events_register() {
  if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
      'page_title' => __('Events Options')
    ));
  }
}

xinc_events_register();

function xinc_events_get_events($page = 1) {
  $time = (time() - (time() % 3600)); // rounded to nearest hour;
  $path = TEMPLATEPATH . '/cache/events-' . $time . '-' . $page .'.json';

  if(!file_exists($path)) {
    $token = 'Token ' . get_field('pretix_api_token', 'options');

    $current_datetime = new DateTimeImmutable();
    $current_datetime = $current_datetime->format('Y-m-d\TH:i:s\Z');

    $api_url = 'https://pretix.eu/api/v1/organizers/transition-network/events/?ordering=date_from&live=true&date_from_after=' . $current_datetime;
    
    $response = wp_remote_get( $api_url, array(
      'headers' => array(
        'Content-Type'  => 'application/json',
        'Authorization' => $token
      )
    ) );
    
    if ( is_array( $response ) && ! is_wp_error( $response ) ) {
      // $headers = $response['headers']; // array of http header lines
      $body    = json_decode($response['body']); // use the content
    }

    $results = $body->results;
  
    if($results) {
  
      $output = array();
      
      foreach($results as $result) {
        // var_dump($result);
        $api_url = 'https://pretix.eu/api/v1/organizers/transition-network/events/' . $result->slug . '/settings?explain=true';
        $response = wp_remote_get( $api_url, array(
          'headers' => array(
            'Content-Type'  => 'application/json',
            'Authorization' => $token
          )
        ) );
        
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
  
          $body = json_decode($response['body']);
          
          $output[] = array(
            'title' => $result->name->en,
            'event_url' => $result->public_url,
            'date_from' => $result->date_from,
            'date_to' => $result->date_to,
            'location' => $result->location->en,
            'geo_lat' => $result->geo_lat,
            'geo_lng' => $result->geo_lng,
            'has_subevents' => $result->has_subevents,
            'description' => $body->frontpage_text->value->en,
            'image_url' => $body->logo_image->value,
          );
        }
      }
  
      // file_put_contents($path, json_encode($output));
      
      return $output;
    }
  
  } else {
    return json_decode(file_get_contents($path), true);
  }

  return;
}

function xinc_events_first_words($sentence, $words = 10) {
  return implode(' ', array_slice(explode(' ', $sentence), 0, $words));
}

function xinc_events_link_urls($sentence) {
  $words = explode(' ', $sentence);
  $output = array();
  foreach($words as $word) {
    if(filter_var($word, FILTER_VALIDATE_URL) === FALSE) {
      $output[] = $word;
    } else {
      $output[] = '<a href="' . $word . '" target="_blank">'. $word . '</a>';
    }
  }

  return implode(' ', $output);
}
?>

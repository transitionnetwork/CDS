<?php
function xinc_events_register() {
  if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
      'page_title' => __('Events Options')
    ));
  }
}

function xinc_events_list_events() {
  
  $token = 'Token ' . 'pb5jpclkev7921ba03jxigs3574vl6alkdmblbjmcyr21gh1ggghe8mla3ol2and';
  $api_url = 'https://pretix.eu/api/v1/organizers/transition-network/events/?is_future=true';
  
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
        // $headers = $response['headers']; // array of http header lines
        $body = json_decode($response['body']); // use the content
        ?>
  
        <img src="<?php echo $body->logo_image->value; ?>" alt="<?php echo $result->name->en; ?>">
        <?php echo $body->frontpage_text->value->en; ?>
        <a href="<?php echo $result->public_url; ?>" target="_blank">Buy tickets</a>
  
      <?php }
    }
  }
}

xinc_events_register();
?>

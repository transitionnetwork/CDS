<?php
$args = array(
  'orderby' => 'meta_value',
  'meta_key' => 'last_logged_in',
  'order' => 'DESC',
);

$user_query = new WP_User_Query( $args );

// User Loop
if ( ! empty( $user_query->results ) ) {
  foreach ( $user_query->results as $user ) {
      $user->ID;

      $the_query = new WP_Query( 'author=' . $user->ID . '&post_status=publish&post_type=initiatives' );
      echo '<p>' . $user->display_name . '</p>';
      // The Loop
      if ( $the_query->have_posts() ) {
          echo '<ul>';
          while ( $the_query->have_posts() ) {
              $the_query->the_post();
              echo '<li>' . get_the_title() . '</li>';
          }
          echo '</ul>';
      } 
      /* Restore original Post Data */
      wp_reset_postdata();
  }
}
?>

<?php if(array_key_exists('ret_emails', $_POST)) {
  switch($_POST['ret_emails']) {
    case 'build' :
      retention_emailing_get_authors();
      break;
    case 'send' :
      retention_emailing_send_emails();
      break;
    case 'bounce' :
      retention_save_bounce_emails();
      break;
  }
} ?>

<main>
  <div class="container">
    <p>Build email retention list?</p>

    <form method="POST">
      <input type="hidden" name="ret_emails" value="build">
      <input type="submit" value="YES">
    </form>

    <hr>

    <p>Send emails to keys <?php echo get_field('email_start', 'options'); ?> to <?php echo get_field('email_stop', 'options'); ?>?

    <form method="POST">
      <input type="hidden" name="ret_emails" value="send">
      <input type="submit" value="YES I AM SURE">
    </form>
    <hr>

    <p>Save bounce list?</p>

    <form method="POST">
      <input type="hidden" name="ret_emails" value="bounce">
      <input type="submit" value="YES">
    </form>
  </div>
</main>

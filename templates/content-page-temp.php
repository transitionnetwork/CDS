<?php if(array_key_exists('ret_emails', $_POST)) {
  switch($_POST['ret_emails']) {
    case 'build' :
      retention_emailing_get_authors();
      break;
    case 'send' :
      if(!strpos($_SERVER['SERVER_NAME'], '.loc')) {
        retention_emailing_send_emails();
      } else {
        var_dump('this is dev');
      }
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

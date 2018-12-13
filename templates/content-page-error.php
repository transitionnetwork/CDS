<main>
  <div class="container">
    <?php $error = get_query_var('error_code');
    switch($error) {
      case '1':
        $message = 'You do not have the correct permissions';
        break;
      case '2':
        $message = 'Administrators can\'t add initiatives as they aren\'t associated with a hub';
        break;
      default:
        $message = 'Unkown error';
        break;
    } ?>
    <div class="alert alert-danger"><?php _e($message); ?></div>
  </div>
</main>

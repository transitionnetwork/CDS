<main>
  <div class="container">
    <?php $error = get_query_var('error');
    switch($error) {
      case 1:
        $message = 'You do not have the correct permissions';
        break;
      default:
        $message = 'Unkown error';
        break;
    } ?>
    <div class="alert alert-danger"><?php _e($message); ?></div>
  </div>
</main>

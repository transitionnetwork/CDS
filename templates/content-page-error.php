<main>
  <div class="container">
    <?php $error = get_query_var('error_code');
    switch($error) {
      case '1':
        $message = __('You do not have the correct permissions', 'tofino');
        break;
      case '2':
        $message = __('Administrators can\'t add initiatives as their accounts aren\'t associated with a hub', 'tofino');
        break;
      default:
        $message = __('Unkown error', 'tofino');
        break;
    } ?>
    <div class="alert alert-danger"><?php echo $message; ?></div>
  </div>
</main>

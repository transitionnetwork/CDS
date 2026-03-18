<div id="login-form" class="xinc-form-container">
  <?php if (count($attributes['errors']) > 0) : ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($attributes['errors'] as $error) : ?>
          <li><?php echo $error; ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

    <!-- Show logged out message if user just logged out -->
  <?php if ($attributes['logged_out']) : ?>
      <div class="alert alert-info">
        <?php _e('You have signed out. Would you like to sign in again?', 'tofino'); ?>
      </div>
  <?php endif; ?>

  <?php if ($attributes['lost_password_sent']) : ?>
      <div class="alert alert-info">
        <?php _e('Check your email for a link to reset your password', 'tofino'); ?>
      </div>
  <?php endif; ?>

  <?php if ($attributes['password_updated']) : ?>
      <div class="alert alert-info">
        <?php _e('Your password has been updated', 'tofino'); ?>
      </div>
  <?php endif; ?>

  <?php if ($attributes['show_title']) : ?>
      <h2><?php _e('Sign In', 'tofino'); ?></h2>
  <?php endif; ?>

  <?php
  wp_login_form(
    array(
      'label_username' => __('Email', 'tofino'),
      'label_log_in' => __('Sign In', 'tofino'),
      'redirect' => $attributes['redirect']
    )
  );
  ?>

  <a href="<?php echo wp_lostpassword_url(); ?>">
      <p><?php _e('Register a new account', 'tofino'); ?></p>
  </a>

  <a href="<?php echo wp_lostpassword_url(); ?>">
      <p><?php _e('Forgot your password?', 'tofino'); ?></p>
  </a>

</div>

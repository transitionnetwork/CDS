<div id="password-lost-form" class="xinc-form-container">
  <?php if (count($attributes['errors']) > 0) : ?>
    <div class="alert alert-danger">
      <ul>
        <?php foreach ($attributes['errors'] as $error) : ?>
          <li><?php echo $error; ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($attributes['show_title']) : ?>
    <h3><?php _e('Forgot Your Password?', 'tofino'); ?></h3>
  <?php endif; ?>

  <p>
    <?php _e("Enter your email address and we'll send you a link you can use to pick a new password.", 'tofino'); ?>
  </p>

  <form id="lostpasswordform" action="<?php echo wp_lostpassword_url(); ?>" method="post">
    <p>
      <label for="user_login"><?php _e('Email', 'tofino'); ?></label>
      <input type="text" name="user_login" id="user_login" placeholder="example@email.com">
    </p>

    <p>
      <input type="submit" name="submit" class="lostpassword-button"
        value="<?php _e('Reset Password', 'tofino'); ?>"/>
    </p>
  </form>
</div>

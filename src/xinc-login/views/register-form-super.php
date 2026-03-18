<div id="register-form" class="xinc-form-container">
  <?php if (count($attributes['errors']) > 0) : ?>
    <?php foreach ($attributes['errors'] as $error) : ?>
      <div class="alert alert-danger">
        <?php echo $error; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (!empty($attributes['form-data'])) {
    $form_data = $attributes['form-data'];
  }; ?>

  <?php if (!empty($attributes['registered'])) : ?>
    <div class="alert alert-info">
      <?php
      printf(
        __('<p>Registration successful for %s.</p><p>The user will receive their login information in their email inbox. You may now <a href="%s">assign a group</a> to this user.</p>', 'tofino'), $attributes['registered'], home_url()
      );
      ?>
    </div>
  <?php endif; ?>

  <?php if ($attributes['show_title']) : ?>
      <h3><?php _e('Register', 'tofino'); ?></h3>
  <?php endif; ?>

  <form id="signupform" action="<?php echo wp_registration_url(); ?>" method="post">
      <p>
          <label for="first_name"><?php _e('First name', 'tofino'); ?></label>
          <input type="text" name="first_name" id="first-name" value="<?php echo $form_data['first_name'] ?? ''; ?>">
      </p>

      <p>
          <label for="last_name"><?php _e('Last name', 'tofino'); ?></label>
          <input type="text" name="last_name" id="last-name" value="<?php echo $form_data['last_name'] ?? ''; ?>">
      </p>

      <p>
          <label for="email"><?php _e('Email', 'tofino'); ?> <strong>*</strong></label>
          <input type="text" name="email" id="email" value="<?php echo $form_data['email'] ?? ''; ?>">
      </p>

      <p class="signup-submit">
        <input type="hidden" name="recaptcha_required" value="false">
        <input type="hidden" name="source_url" value="<?php echo get_the_permalink(); ?>">
        <input type="submit" name="submit" class="register-button" value="<?php _e('Register', 'tofino'); ?>"/>
      </p>
  </form>
</div>

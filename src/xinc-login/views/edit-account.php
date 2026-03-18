<div id="register-form" class="xinc-form-container">
  <?php if ($attributes['show_title']) : ?>
      <h3><?php _e('Edit Account', 'tofino'); ?></h3>
  <?php endif; ?>

  <?php if (count($attributes['errors']) > 0) : ?>
    <?php foreach ($attributes['errors'] as $error) : ?>
      <div class="alert alert-danger">
        <?php echo $error; ?>
      </div>
    <?php endforeach; ?>
  <?php endif; ?>

  <?php if (!empty($attributes['updated'])) : ?>
    <div class="alert alert-success">
      <?php _e('Your account has been updated', 'tofino'); ?>
    </div>
  <?php endif; ?>

  <?php $form_data = $attributes['form-data']; ?>

  <form id="editform" action="<?php echo esc_url(add_query_arg('id', $attributes['user_id'], the_permalink())); ?>" method="post">
    <p>
      <label for="first_name"><?php _e('First name', 'tofino'); ?></label>
      <input type="text" name="first_name" id="first-name" value="<?php echo $form_data['first_name']; ?>">
    </p>

    <p>
      <label for="last_name"><?php _e('Last name', 'tofino'); ?></label>
      <input type="text" name="last_name" id="last-name" value="<?php echo $form_data['last_name']; ?>">
    </p>

    <p>
      <label for="email"><?php _e('Email', 'tofino'); ?> <strong>*</strong></label>
      <input type="text" name="email" id="email" value="<?php echo $form_data['email']; ?>">
    </p>

    <p>
      <label for="pass1"><?php _e('New password', 'tofino') ?></label>
      <input type="password" name="pass1" id="pass1" class="input" size="20" value="" autocomplete="off" />
    </p>

    <p>
      <label for="pass2"><?php _e('Repeat new password', 'tofino') ?></label>
      <input type="password" name="pass2" id="pass2" class="input" size="20" value="" autocomplete="off" />
    </p>

    <p><?php _e('Leave the password fields blank if you don\'t want to change your password', 'tofino'); ?>

    <p class="signup-submit">
      <input type="submit" name="submit" class="register-button" value="<?php _e('Update', 'tofino'); ?>"/>
    </p>
  </form>
</div>

<section>
  <h2><?php _e('Account Details', 'tofino'); ?></h2>
  <div class="account-details">
  <?php
  echo '<strong>Username:</strong> ' . wp_get_current_user()->user_login . '<br />';
  echo '<strong>Email:</strong> ' . wp_get_current_user()->user_email . '<br />';
  ?>
  </div>
  <ul class="button-group">
    <li><a href="<?php echo home_url('member-edit-account'); ?>" class="btn btn-primary btn-sm"><?php _e('Edit Account Details', 'tofino'); ?></a></li>
    <?php if (is_user_role('super_hub')) : ?>
      <li><a href="<?php the_permalink(270); ?>" class="btn btn-primary btn-sm"><?php _e('View All Hub Users', 'tofino'); ?></a></li>
    <?php endif; ?>
  </ul>
</section>

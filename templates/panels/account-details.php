<section>
  <h2><?php _e('Account Details', 'tofino'); ?></h2>
  <ul class="account-details">
    <li><label>First Name:</label><?php echo wp_get_current_user()->first_name; ?></li>
    <li><label>Last Name:</label><?php echo wp_get_current_user()->last_name; ?></li>
    <li><label>Email:</label><?php echo wp_get_current_user()->user_email; ?></li>
</ul>
  <ul class="button-group">
    <li><a href="<?php echo home_url('member-edit-account'); ?>" class="btn btn-primary btn-sm"><?php _e('Edit Account Details', 'tofino'); ?></a></li>
    <?php if (is_user_role('super_hub')) : ?>
      <li><a href="<?php the_permalink(270); ?>" class="btn btn-primary btn-sm"><?php _e('View All Hub Users', 'tofino'); ?></a></li>
    <?php endif; ?>
  </ul>
</section>

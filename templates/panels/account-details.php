<section>
  <h2>Account Details</h2>
  <div class="account-details">
  <?php
  echo '<strong>Username:</strong> ' . wp_get_current_user()->user_login . '<br />';
  echo '<strong>Email:</strong> ' . wp_get_current_user()->user_email . '<br />';
  ?>
  </div>
  <ul class="button-group">
    <li><a href="<?php echo get_permalink(285); ?>" class="btn btn-primary btn-sm">Edit Account Details</a></li>
    <?php if (is_user_role('super_hub')) : ?>
      <li><a href="<?php the_permalink(270); ?>" class="btn btn-primary btn-sm">View All Hub Users</a></li>
    <?php endif; ?>
  </ul>
</section>

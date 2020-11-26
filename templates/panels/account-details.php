<?php
$user_role = wp_get_current_user()->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));
?>
<section>
  <h2><?php _e('Account Details', 'tofino'); ?></h2>
  <ul class="account-details">
    <li><label><?php _e('First Name:', 'tofino'); ?></label><?php echo wp_get_current_user()->first_name; ?></li>
    <li><label><?php _e('Last Name:', 'tofino'); ?></label><?php echo wp_get_current_user()->last_name; ?></li>
    <li><label><?php _e('Email:', 'tofino'); ?></label><?php echo wp_get_current_user()->user_email; ?></li>
    <?php if($user_role) : ?>
      <li><label><?php _e('Role:', 'tofino'); ?></label><?php echo $user_human_role; ?></li>
    <?php endif; ?>
</ul>
  <ul class="button-group">
    <li><a href="<?php echo parse_post_link(467); ?>" class="btn btn-primary btn-sm"><?php echo svg('pencil'); ?><?php _e('Edit Account Details', 'tofino'); ?></a></li>
    <?php if (is_user_role('super_hub')) : ?>
      <li><a href="<?php echo parse_post_link(270); ?>" class="btn btn-primary btn-sm"><?php _e('View All Hub Users', 'tofino'); ?></a></li>
    <?php endif; ?>
  </ul>
</section>

<?php if(is_user_role('initiative')) { ?>
  <section>
    <h2><?php _e('Request Hub Level Access', 'tofino'); ?></h2>
    <?php if(!get_user_meta(get_current_user_id(), 'hub_access_requested')) { ?>
      <p><?php _e('Please click the button below to request your role to be upgraded to <strong>Hub User</strong>.'); ?></p>
      <form action="" method="POST">
        <input type="hidden" name="type" value="request_hub_access">
        <input type="hidden" name="user_id" value="<?php echo get_current_user_id(); ?>">
        <input type="submit" name="submit" id="submit" value="Send request">
      </form>
    <?php } else { ?>
      <p><?php _e('You requested hub access on: ', 'tofino'); ?>&nbsp;<?php echo get_user_meta(get_current_user_id(), 'hub_access_requested', true); ?></p>
      <p><?php _e('Please await contact from your hub administrator', 'tofino'); ?></p>
    <?php } ?>
  </section>
<?php } ?>
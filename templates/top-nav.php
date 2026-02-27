<?php
if(is_user_logged_in(  )) {
  $user_role = wp_get_current_user()->roles[0];
  $user_human_role = $user_role ? wp_roles()->get_names()[ $user_role ] : '';
} else {
  $user_role = null;
}

$hub_object = null;

if(is_user_role(array('hub', 'super_hub'))) {
  $hub_id = get_field('hub_user', wp_get_current_user());
  $hub_object = get_term_by('term_id', $hub_id, 'hub'); 
}
?>

<div class="user-details d-flex align-items-center">
  <?php
  if(!is_user_logged_in()) { ?>
    <div>
      <span class="tag beta">beta</span>
      <?php _e('Not logged in', 'tofino'); ?> | <a href="<?php echo get_the_permalink(459); ?>"><?php _e('Sign in', 'tofino'); ?></a> | <a href="<?php echo get_the_permalink(460); ?>"> <?php _e('Register', 'tofino'); ?></a>
    </div>
  <?php } else { ?>
    <div>
      <?php if($hub_object) : ?>
        <div class="tag hub"><?php echo $hub_object->name; ?></div>
      <?php endif; ?>
  
      <?php _e('Logged in as', 'tofino'); ?> <?php echo wp_get_current_user()->user_email; ?> <?php echo ($user_role) ? '(' . $user_role . ')' : ''; ?> | <a href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', 'tofino'); ?></a>
    </div>
  <?php } ?>

</div>

<?php if(is_user_logged_in(  )) { ?>
  <div class="d-flex align-items-center">
    <a href="<?php echo home_url('account'); ?>">My Dashboard</a>
  </div>
<?php } ?>

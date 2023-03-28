<?php
if(is_user_logged_in(  )) {
  $user_role = wp_get_current_user()->roles[0];
  $user_human_role = ucwords(str_replace('_', ' ', $user_role));
} else {
  $user_role = null;
}

$hub_object = null;

if(is_user_role('hub')) {
  $hub_id = get_field('hub_user', wp_get_current_user());
  $hub_object = get_term_by('term_id', $hub_id, 'hub'); 
}
?>

<div class="user-details d-flex align-items-center">
  <?php
  if(!is_user_logged_in()) { ?>
    <div>
      <span class="tag beta">beta</span>
      <?php _e('Not logged in', 'tofino'); ?> | <a href="<?php echo parse_post_link(459); ?>"><?php _e('Sign In', 'tofino'); ?></a>
    </div>
  <?php } else { ?>
    <?php if($user_role) : ?>
      <div class="tag role"><?php _e('Role', 'tofino'); ?>: <?php echo $user_human_role; ?></div>
    <?php endif; ?>

    <?php if($hub_object) : ?>
      <div class="tag hub"><?php echo $hub_object->name; ?></div>
    <?php endif; ?>

    <?php _e('Logged in as', 'tofino'); ?> <?php echo wp_get_current_user()->user_email; ?> | <a href="<?php echo wp_logout_url(home_url()); ?>"><?php _e('Logout', 'tofino'); ?></a>
  <?php } ?>

</div>

<?php if(shortcode_exists('language-switcher')) { ?>
  <div class="d-flex align-items-center">
    <div id="lang-switch" class="mr-2">
      <?php echo do_shortcode('[language-switcher]'); ?>
    </div>
  </div>
<?php } ?>

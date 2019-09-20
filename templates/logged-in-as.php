<?php
$user_role = wp_get_current_user()->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));

if(is_user_role('hub')) {
  $hub_id = get_field('hub_user', wp_get_current_user());
  $hub = get_term_by('term_id', $hub_id, 'hub'); 
}
?>

<div class="user-details">
  <?php
  if(is_user_logged_in()) : ?>
    <?php _e('Logged in as', 'tofino'); ?> <?php echo wp_get_current_user()->user_login; ?>
  <?php else : ?>
    <?php _e('Not logged in', 'tofino'); ?> | <a href="<?php echo get_the_permalink(459); ?>"><?php _e('Login', 'tofino'); ?></a>
  <?php endif; ?>

  <?php if($user_role) : ?>
    <div class="tag role"><?php _e('Role', 'tofino'); ?>: <?php echo $user_human_role; ?></div>
  <?php endif; ?>

  <?php if($hub) : ?>
    <div class="tag hub"><?php echo $hub->name; ?></div>
  <?php endif; ?>
</div>

<div>
  <span class="tag beta">BETA</span>
</div>

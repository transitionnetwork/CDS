<?php
$user_role = wp_get_current_user()->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));
$user_hub = get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?>

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

  <?php if($user_hub) : ?>
    <div class="tag hub"><?php echo $user_hub; ?></div>
  <?php endif; ?>
</div>

<div>
  <span class="tag beta">BETA</span>
</div>

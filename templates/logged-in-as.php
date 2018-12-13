<?php
$user_role = wp_get_current_user()->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));
$user_hub = get_the_terms(wp_get_current_user(), 'hub')[0]->name;

if(is_user_logged_in()) : ?>
  Logged in as <?php echo wp_get_current_user()->user_login; ?>
<?php else : ?>
  Not logged in | <a href="<?php home_url(); ?>">Login</a>
<?php endif; ?>

<?php if($user_role) : ?>
  <div class="tag role">User Role: <?php echo $user_human_role; ?></div>
<?php endif; ?>

<?php if($user_hub) : ?>
  <div class="tag hub">Regional Hub: <?php echo $user_hub; ?></div>
<?php endif; ?>

<?php
$current_user = wp_get_current_user();
$user_role = $current_user->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));
$user_hub = get_the_terms($current_user, 'hub');
$user_hub_name = $user_hub[0]->name;

if(is_user_logged_in()) : ?>
  Logged in as <?php echo $current_user->user_login; ?>
<?php else : ?>
  Not logged in | <a href="<?php the_permalink(20); ?>">Login</a>
<?php endif; ?>

<?php if($user_role) : ?>
  <div class="tag role"><?php echo $user_human_role; ?> User</div>
<?php endif; ?>

<?php if($user_hub) : ?>
  <div class="tag hub">Hub: <?php echo $user_hub_name; ?></div>
<?php endif; ?>

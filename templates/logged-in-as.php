<?php $current_user = wp_get_current_user();
if(is_user_logged_in()) : ?>
  I am logged in as <?php echo $current_user->user_login; ?>.&nbsp;
<?php else : ?>
  I am not logged in.&nbsp;
<?php endif; ?>

<?php if($current_user->roles[0] == 'administrator') : ?>
  I can see all the private data and edit everything! :)
<?php elseif($current_user->roles[0] == 'author') : ?>
  I can only see my own hubs' private data and edit my own hubs! :|
<?php else : ?>
  I can't see any private data and I can't edit anything :(
<?php endif; ?>

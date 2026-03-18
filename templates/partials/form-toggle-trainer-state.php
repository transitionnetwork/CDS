<?php if(is_user_trainer_admin()) { ?>
  <form action="" method="POST" class="inline-flex flex-wrap gap-2 items-center">
    <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">

    <?php $confirm_message = __('Are you sure you want to delete this trainer?', 'tofino'); ?>

    <?php if(get_post_status() === 'pending' && is_user_trainer_admin()) { ?>
      <button class="btn btn-success btn-sm" name="trainer_update" value="publish"><?php echo svg('check'); ?>Publish</button>
      <button class="btn btn-error btn-sm" name="trainer_update" value="trash" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?>Delete</button>
    <?php } else { ?>
      <button class="btn btn-error btn-sm" name="trainer_update" value="pending"><?php echo svg('x'); ?>Unpublish</button>
    <?php } ?>
  </form>
<?php } ?>

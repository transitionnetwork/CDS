<?php if(is_user_trainer_admin()) { ?>
  <form action="" method="POST" class="mt-3">
    <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">

     <?php $confirm_message = __('Are you sure you want to delete this trainer?', 'tofino'); ?>

    <?php if(get_post_status() === 'pending' && is_user_trainer_admin()) { ?>
      <button class="btn btn-success btn-sm" name="trainer_update" value="publish"><?php echo svg('check'); ?>Publish Trainer</button>
      <button class="btn btn-danger btn-sm" name="trainer_update" value="trash" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?>Delete Trainer</button>
    <?php } else { ?>
      <button class="btn btn-danger btn-sm" name="trainer_update" value="pending"><?php echo svg('x'); ?>Unpublish Trainer</button>
    <?php } ?>

    <?php if(is_user_trainer_admin()) { ?>
      <div class="trainer-created">
        <?php _e('Created: '); ?><?php echo get_the_date(); ?>
      </div>
    <?php } ?>
  </form>
<?php } ?>


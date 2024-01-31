<form action="" method="POST" class="publish-delete">
  <div>
    <button name="publish" value="<?php echo ($args['post_id']); ?>" class="btn btn-success btn-sm">
      <?php echo svg('check'); ?><?php _e('Publish', 'tofino'); ?>
    </button>
  
    <?php $confirm_message = __('Are you sure you want to delete this group? This is permanent!', 'tofino'); ?>
    <button name="trash_group_id" value="<?php echo ($args['post_id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo $confirm_message; ?>')">
      <?php echo svg('trashcan'); ?><?php _e('Delete', 'tofino'); ?>
    </button>
  </div>
</form>

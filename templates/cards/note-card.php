<?php $confirm_message = __('Are you sure you want to delete this note?', 'tofino'); ?>

<div class="note-card panel">
  <p>
    <em><?php echo get_the_modified_date('d M Y - H:i:s', $post); ?></em>
  </p>  
  <p>
    <strong><?php echo get_field('note_type')['label']; ?></strong>
  </p>
  
  <?php echo get_field('note_content'); ?>
  
  <div>
    <div class="button-block">
      <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg(array('edit_post' => get_the_ID()), '/edit-note'); ?>">
        <?php echo svg('pencil'); ?><?php _e('Edit note', 'tofino'); ?>
      </a>
    </div>
    <div class="button-block">
      <form action="" method="post">
        <button name="unpublish" value="<?php echo $post->ID; ?>" class="btn btn-danger btn-sm btn-last" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?><?php _e('Delete note', 'tofino'); ?></button>
      </form>
    </div>
  </div>
</div>

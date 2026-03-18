<?php $confirm_message = __('Are you sure you want to delete this note?', 'tofino'); ?>

<div class="note-card panel">
  <div class="flex flex-wrap items-center gap-2 mb-2">
    <em class="text-sm"><?php echo get_the_modified_date('d M Y - H:i:s', $post); ?></em>
    <span class="badge badge-outline text-xs"><?php echo get_field('note_type')['label']; ?></span>
  </div>

  <div class="rich-text"><?php echo get_field('note_content'); ?></div>

  <div class="flex flex-wrap gap-2 mt-2">
    <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg(array('edit_post' => get_the_ID()), '/edit-note'); ?>">
      <?php echo svg('pencil'); ?><?php _e('Edit note', 'tofino'); ?>
    </a>
    <form action="" method="post" class="inline">
      <button name="unpublish" value="<?php echo $post->ID; ?>" class="btn btn-error btn-sm" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?><?php _e('Delete note', 'tofino'); ?></button>
    </form>
  </div>
</div>

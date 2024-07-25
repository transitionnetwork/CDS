  <form action="<?php the_permalink() ?>" method="POST" id="change-author">
    <input name="post_id" type="hidden" value="<?php echo $post->ID; ?>">
    <label for="authors">Update primary editor ID</label>
    
    <div class="d-flex align-items-center">
      <input type="number" name="update_group_author_id" value="<?php echo $post_author_id; ?>" placeholder="Author ID" min="1">
      <button type="submit" class="btn btn-primary btn-sm">Update</button>
    </div>
      <div class="mt-2"><a class="btn btn-sm btn-warning" href="/wp-admin/users.php" target="_blank"><?php echo svg('eye'); ?>View user list</a></div>
  </form>

<?php if(is_user_role(array('administrator'))) { ?>
  <h3>Primary Author</h3>
  <?php $post_author_id = get_the_author_meta('ID'); ?>
  <label>Primary author</label>
  <?php echo get_the_author_meta('display_name'); ?><br/>
  <a href="mailto:<?php echo get_the_author_meta('user_email'); ?>"><?php echo get_the_author_meta('user_email'); ?></a>

  <form action="<?php the_permalink() ?>" method="POST" id="change-author">
    <input name="post_id" type="hidden" value="<?php echo $post->ID; ?>">
    <label for="authors">Update primary author ID</label>
    
    <div class="d-flex align-items-center">
      <input type="number" name="update_group_author_id" value="<?php echo $post_author_id; ?>" placeholder="Author ID" min="1">
      <button type="submit" class="btn btn-primary btn-sm">Update</button>
    </div>
     <div class="mt-2"><a class="btn btn-sm btn-warning" href="/wp-admin/users.php" target="_blank"><?php echo svg('eye'); ?>View user list</a></div>
  </form>
<?php } ?>

<?php $post_author_id = get_the_author_meta('ID'); ?>
<div class="panel">
  <h3>Primary Author</h3>
  <?php if(get_environment() === 'dev') { ?>
    <label>ID</label>
    <div><?php echo $post_author_id; ?></div>
  <?php } ?>
  <label>Name</label>
  <div><?php echo get_the_author_meta('display_name'); ?></div>
  <label>Email</label><a href="mailto:<?php echo get_the_author_meta('user_email'); ?>"><?php echo get_the_author_meta('user_email'); ?></a>
</div>

<form action="<?php the_permalink() ?>" method="POST" id="change-author" class="panel">
  <h3 for="authors">Update author ID</h3>
  <p><a class="btn btn-sm btn-warning mb-3" href="/wp-admin/users.php" target="_blank"><?php echo svg('eye'); ?>View user list</a></p>
  <input name="post_id" type="hidden" value="<?php echo $post->ID; ?>">
  
  <div class="mt-3 d-flex align-items-center">
    <input type="number" name="authors" value="<?php echo $post_author_id; ?>" placeholder="Author ID" min="1">
    <button type="submit" class="btn btn-primary btn-sm">Update</button>
  </div>
</form>

<?php if(get_field('private_email')) { ?>
  <div class="panel">
    <h3><?php echo get_field_object('private_email')['label']; ?></h3>
    <a href="mailto:<?php echo get_field('private_email'); ?>"><?php echo get_field('private_email'); ?></a>
  </div>
<?php } ?>

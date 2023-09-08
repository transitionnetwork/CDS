<h3>Authors</h3>
<?php $post_author_id = get_the_author_meta('ID'); ?>
<label>Primary</label>
<?php echo get_the_author_meta('display_name'); ?> (<?php echo $post_author_id; ?>)<br/>
<a href="mailto:<?php echo get_the_author_meta('user_email'); ?>"><?php echo get_the_author_meta('user_email'); ?></a>

<form action="<?php the_permalink() ?>" method="POST" id="change-author">
  <input name="post_id" type="hidden" value="<?php echo $post->ID; ?>">
  <label for="authors">Update author ID</label>
  
  <div class="d-flex align-items-center">
    <input type="number" name="update_group_author_id" value="<?php echo $post_author_id; ?>" placeholder="Author ID" min="1">
    <button type="submit" class="btn btn-primary btn-sm">Update</button>
  </div>
   <p class="mt-2"><a class="btn btn-sm btn-warning" href="/wp-admin/users.php" target="_blank"><?php echo svg('eye'); ?>View user list</a></p>
</form>

<div class="mt-4">
  <label>Add co-author</label>
  <form action="<?php the_permalink(); ?>" method="POST">
    <input type="hidden" name="ma_post_id" value="<?php echo $post->ID; ?>">
    <div class="d-flex align-items-center">
      <input type="email" name="ma_add_co_author_email" placeholder="email address">
      <button type="submit" class="btn btn-primary btn-sm">Add</button>
    </div>
  </form>
</div>

<?php $co_authors = ma_get_co_authors($post->ID); ?>
<?php if($co_authors) { ?>
  <div class="mt-4">
    <label>Co-authors</label>
    <?php foreach($co_authors as $user_id) { ?>
      <?php $author_email = get_userdata($user_id)->user_email; ?>
      <div>
        <?php echo $author_email; ?>
        <form action="<?php the_permalink(); ?>" method="POST" class="d-inline">
          <input type="hidden" name="ma_post_id" value="<?php echo $post->ID; ?>">
          <button type="submit" name="ma_make_primary_author" value="<?php echo $user_id; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure? This will overwrite the current primary author');">Promote</button>
          <button type="submit" name="ma_remove_co_author_id" value="<?php echo $user_id; ?>" class="btn btn-danger btn-sm">Delete</button>
        </form>
      </div>
    <?php } ?>
  </div>
<?php } ?>

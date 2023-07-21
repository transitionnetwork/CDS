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
  
  <?php if(is_user_role('administrator')) {
    //let admins view all users
    $args = array();  
  } else {
    $args = array(
      'meta_query' => array(
        array(
          'key' => 'parent_id',
          'value' => get_current_user_id()
        )
      )
    );
  }
  
  $users = get_users($args); 
  ?>
  
  <?php if($users) { ?>
    <form action="<?php the_permalink() ?>" method="POST" id="change-author" class="mt-3">
      <div>
        <input type="number" name="authors" value="<?php echo $post_author_id; ?>" placeholder="Author ID" min="1">
        
        <input name="post_id" type="hidden" value="<?php echo $post->ID; ?>">
      </div>
      <input type="submit" value="Update primary Author ID" class="mt-2">
      <div class="mt-2">
        <a class="btn btn-sm btn-warning mb-3" href="/wp-admin/users.php" target="_blank"><?php echo svg('eye'); ?>View user list</a>
      </div>
    </form>
  
  <?php } ?>
</div>

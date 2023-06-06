<?php $post_author_id = get_the_author_meta('ID'); ?>
<div class="panel">
  <h3>Author</h3>
  <?php if(get_environment() === 'dev') { ?>
    <label>ID</label>
    <div><?php echo $post_author_id; ?></div>
  <?php } ?>
  <label>Name</label>
  <div><?php echo get_the_author_meta('display_name'); ?></div>
  <label>Email</label><a href="mailto:<?php echo get_the_author_meta('user_email'); ?>"><?php echo get_the_author_meta('user_email'); ?></a>
</div>

<?php $args = array();

if(is_user_role('administrator')) {
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
  <form action="<?php the_permalink() ?>" method="POST" id="change-author" class="panel">
    <h3 for="authors">Update author</h3>
    <div class="mt-3">
      <input type="number" name="authors" value="<?php echo $post_author_id; ?>" placeholder="Author ID" min="1">
      
      <input name="post_id" type="hidden" value="<?php echo $post->ID; ?>">
    </div>
    <input type="submit" value="Change" class="mt-3">
  </form>
<?php } ?>

<?php if(get_field('private_email')) { ?>
  <div class="panel">
    <h3><?php echo get_field_object('private_email')['label']; ?></h3>
    <a href="mailto:<?php echo get_field('private_email'); ?>"><?php echo get_field('private_email'); ?></a>
  </div>
<?php } ?>

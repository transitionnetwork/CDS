<?php $post_author_id = get_the_author_meta('ID'); ?>
<div class="panel mt-4">
  <h3>Author</h3>
  <label>Name</label><?php echo get_the_author_meta('display_name'); ?>
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
    <label for="authors">Update author</label>


    <p>
      <select name="authors">
        <?php foreach($users as $user) { ?>
          <option value="<?php echo $user->ID; ?>" <?php echo ($user->ID === $post_author_id) ? 'selected' : ''; ?>><?php echo $user->display_name; ?> | <?php echo $user->user_email; ?></option>
        <?php } ?>
      </select>
      <input name="post_id" type="hidden" value="<?php echo $post->ID; ?>">
    </p>
    <p>
      <input type="submit" value="Change">
    </p>
  </form>
<?php } ?>

<?php if(get_field('private_email')) { ?>
  <label><?php echo get_field_object('private_email')['label']; ?>:</label>
  <a href="mailto:<?php echo get_field('private_email'); ?>"><?php echo get_field('private_email'); ?></a>
<?php } ?>

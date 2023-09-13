<h3>Group Authors</h3>

<p><strong>Please add as many group co-authors as possible.</strong></p>
<p>These email addresses are to ensure we don't lose contract with your group<p>
<p>Author email addresses <strong>are not displayed publicly</strong></p>

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
    <label>Co-authors added to group</label>
    <?php foreach($co_authors as $user_id) { ?>
      <?php $author_email = get_userdata($user_id)->user_email; ?>
      <div class="mt-2">
        <?php echo $author_email; ?>
        <form action="<?php the_permalink(); ?>" method="POST" class="d-inline">
          <input type="hidden" name="ma_post_id" value="<?php echo $post->ID; ?>">
          <?php if(is_user_role(array('administrator'))) { ?>
            <button type="submit" name="ma_make_primary_author" value="<?php echo $user_id; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure? This will overwrite the current primary author');">Promote</button>
          <?php } ?>
          <button type="submit" name="ma_remove_co_author_id" value="<?php echo $user_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this user?');">Delete</button>
        </form>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<?php $waiting_co_authors = get_option('waiting_co_authors'); ?>
<?php if($waiting_co_authors) { ?>
  <?php foreach($waiting_co_authors as $waiting_co_author) { ?>
    <?php if((int)$waiting_co_author['post_id'] === $post->ID) { ?>
      <div class="mt-4">
        <label>Co-authors invited to group</label>
        <?php foreach($waiting_co_authors as $waiting_co_author) { ?>
          <?php if((int)$waiting_co_author['post_id'] === $post->ID) { ?>
            <div class="mt-2">
              <?php echo $waiting_co_author['user_email']; ?>
              <form action="<?php the_permalink(); ?>" method="POST" class="d-inline">
                <button type="submit" name="ma_remove_co_waiting_author_email" value="<?php echo $waiting_co_author['user_email']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove this user?');">Delete</button>
              </form>
            </div>
          <?php } ?>
        <?php } ?>
      </div>
    <?php } ?>
    <?php break; ?>
  <?php } ?>
<?php } ?>

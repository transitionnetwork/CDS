<div class="panel">
  <h3>Add Co-Author</h3>
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
  <div class="panel">
    <h3>Co-authors</h3>
    <ul>
      <?php foreach($co_authors as $user_id) { ?>
        <?php $author_email = get_userdata($user_id)->user_email; ?>
        <li>
          <?php echo $author_email; ?>
          <form action="<?php the_permalink(); ?>" method="POST">
            <input type="hidden" name="ma_post_id" value="<?php echo $post->ID; ?>">
            <button type="submit" name="ma_remove_co_author_id" value="<?php echo $user_id; ?>" class="btn btn-danger btn-sm">Remove</button>
          </form>
        </li>
      <?php } ?>
    </ul>
  </div>
<?php } ?>

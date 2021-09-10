<?php if(is_user_trainer_admin()) { ?>
  <form action="" method="POST" class="mt-3">
    <input type="hidden" name="post_id" value="<?php echo get_the_ID(); ?>">
    <?php if(get_post_status() === 'pending' && is_user_trainer_admin()) { ?>
      <button class="btn btn-success btn-sm" name="trainer_update" value="publish">Publish Trainer</button>
    <?php } else { ?>
      <button class="btn btn-danger btn-sm" name="trainer_update" value="pending">Unpublish Trainer</button>
    <?php } ?>
  </form>
<?php } ?>


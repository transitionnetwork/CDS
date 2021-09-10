<?php $author_id = get_the_author_meta('ID'); ?>

<?php if(is_user_trainer_admin() || ($author_id === get_current_user_id())) { ?>
  <div class="mt-3">
    <p><a href="<?php echo add_query_arg('edit_post', get_the_ID(), parse_post_link(6741)); ?>" class="btn btn-dark btn-sm"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a></p>
  
  </div>
<?php } ?>

<ul class="button-group">
  <div class="mb-5">
    <?php get_search_form(); ?>
  </div>

  <?php if(is_user_logged_in()) { ?>
    <li><a class="btn btn-outline" href="<?php echo parse_post_link(13); ?>"><?php echo svg('plus'); ?><?php _e('Add New Group', 'tofino'); ?></a></li>
    <li><a class="btn btn-outline" href="<?php echo parse_post_link(6739); ?>"><?php echo svg('plus'); ?><?php _e('Add New Trainer', 'tofino'); ?></a></li>
  <?php } else { ?>
    <li>
      <div class="mb-2">
        <?php _e('To add a group or hub please:', 'tofino'); ?><br/>
      </div>
      <a class="btn btn-outline" href="<?php echo parse_post_link(459); ?>"><?php echo svg('key'); ?><?php _e('Sign in', 'tofino'); ?></a>
      <span class="mx-1">
        <?php _e('or', 'tofino'); ?>
      </span>
      <a class="btn btn-outline" href="<?php echo parse_post_link(460); ?>"><?php echo svg('key'); ?><?php _e('Register', 'tofino'); ?></a>
    </li>
  <?php } ?>
    
  <?php if(is_user_logged_in()) { ?>
    <li><a href="<?php echo parse_post_link(6185); ?>" class="btn  btn-outline"><?php echo svg('plus'); ?><?php _e('Add New Hub', 'tofino'); ?></a></li>
  <?php } ?>
</ul>

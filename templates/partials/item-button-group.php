  <div class="btn-group">
    <a class="btn btn-primary btn-sm" href="<?php echo get_the_permalink(); ?>"><?php echo svg('eye'); ?><?php _e('View', 'tofino'); ?></a>

    <?php if(is_user_role(array('administrator', 'super_hub'))) {  ?>

      <?php $current_page_url = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; ?>

      <?php if($current_page_url === home_url() . '/') { 
        $current_page_url .= 'search-groups';
        //deal with redirection to homepage failing
      } ?>
      
      <a class="btn btn-sm btn-secondary" href="<?php echo add_query_arg(array('initiative_id' => get_the_ID(), 'source' => $current_page_url), '/add-note'); ?>">
        <?php echo svg('plus'); ?>Note
      </a>
    <?php } ?>
    
    <?php if(can_write_initiative($post)) { ?>
      <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('edit_post', $post->ID, get_the_permalink(69)); ?>"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a>

      <?php if(get_post_status($post) === 'publish') { ?>
        <?php $confirm_message = __('Are you sure you want to unpublish this group? You can re-publish it from the Dashboard', 'tofino'); ?>
        <form action="" method="post">
          <button name="unpublish" value="<?php echo $post->ID; ?>" class="btn btn-danger btn-sm btn-last" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('x'); ?><?php _e('Unpublish', 'tofino'); ?></button>
        </form>
      <?php } ?>
    <?php } ?>
  </div>
  
  <?php if (can_publish_initiative($post) && !is_post_published($post)) { ?>
    <?php get_template_part('templates/buttons/publish-delete', null, array('post_id' => $post->ID)); ?>
  <?php } ?>

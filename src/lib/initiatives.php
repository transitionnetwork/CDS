<?php
function list_initiatives($posts) {
  if ($posts) { ?>
    <table class="item-list">
        <tr>
          <th class="col-a"><?php _e('Initiative', 'tofino'); ?></th>
          <th class="col-b"><?php _e('Hub', 'tofino'); ?></th>
          <?php if(can_view_any_healthcheck()) { ?>
            <th class="col-b"><?php _e('Last Healthcheck', 'tofino'); ?></th>
          <?php } ?>
          <th class="col-c"></th>
        </tr>
        
        <?php foreach ($posts as $post) : ?>
          <?php setup_postdata($post);
          $author_object = get_user_by('id', get_the_author_meta('ID'));
          $author_hub_name = get_the_terms($author_object, 'hub')[0]->name; ?>
          <tr>
            <td>
              <a href="<?php the_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a>
              <span class="status">
                <?php $pending_message = __('Pending approval', 'tofino'); ?>
                <?php echo ($post->post_status == 'publish') ? '' : '<span class="btn btn-sm btn-dark btn-disabled">' . svg('alert') . $pending_message . '</span>'; ?>
              </span>
            </td>
            <td>
              <?php $hub_slug = get_term_by('id', get_field('hub_tax', $post->ID), 'hub')->slug; ?>
              <a href="<?php echo add_query_arg('hub_name', $hub_slug,  get_post_type_archive_link('initiatives')); ?>"><?php echo get_hub_by_id(get_field('hub_tax', $post->ID)); ?></a>
            </td>
            <td>
              <?php if(can_view_healthcheck($post)) {
                echo get_latest_healthcheck($post->ID);
              } ?>
            </td>
            <td class="text-right">
              <a class="btn btn-primary btn-sm" href="<?php the_permalink($post->ID); ?>"><?php echo svg('eye'); ?><?php _e('View', 'tofino'); ?></a>
              
              <?php if(can_write_initiative($post)) { ?>
                <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('edit_post', $post->ID, home_url('edit-initiative')); ?>"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a>
                <a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link($post->ID); ?>" onclick="return confirm('Are you sure you want to remove this hub?')"><?php echo svg('trashcan'); ?><?php _e('Delete', 'tofino'); ?></a>
              <?php } ?>
              <?php if(can_publish_initiative($post) && !is_post_published($post)) {
                render_publish_button($post->ID);
              } ?>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td><span class="total">
            <?php echo count($posts); ?> items
          </span></td>
        </tr>
    </table>
  <?php 
  } ?>

  <?php if (!$posts) { ?>
    <?php _e('There aren\'t any initiatives', 'tofino'); ?>
  <?php  }
  wp_reset_postdata();
}

<?php
function get_initiative_by_id($post_id) {
  if(false === get_transient('initiative_list_item_' . $post_id)) {
    $author_object = get_user_by('id', get_the_author_meta('ID'));
    $author_hub_name = get_the_terms($author_object, 'hub')[0]->name;
    $hub_object = wp_get_post_terms($post_id, 'hub')[0];
    $data = array(
      'link' => get_the_permalink($post_id),
      'title' => get_the_title($post_id),
      'status' => get_post_status($post_id),
      'hub_slug' => $hub_object->slug,
      'hub_name' => $hub_object->name,
      'latest_healthcheck' => get_latest_healthcheck($post_id)
    );
    set_transient('initaitve_list_item_' . $post_id, 60 * 60 * 72);
  } else {
    $data = get_transient('initiative_list_item_' . $post_id);
  }
  return $data;
}

function list_initiatives($post_ids) {
  if ($post_ids) { ?>
    <table class="item-list">
        <tr>
          <th class="col-a"><?php _e('Initiative', 'tofino'); ?></th>
          <th class="col-b"><?php _e('Hub', 'tofino'); ?></th>
          <?php if(can_view_any_healthcheck()) { ?>
            <th class="col-b"><?php _e('Last Healthcheck', 'tofino'); ?></th>
          <?php } ?>
          <th></th>
        </tr>
        
        <?php foreach ($post_ids as $post_id) :
          $data = get_initiative_by_id($post_id);
          $post = get_post($post_id); ?>
          <tr>
            <td>
              <a href="<?php echo $data['link']; ?>"><?php echo $data['title'] ?></a>
              <span class="status">
                <?php $pending_message = __('Pending approval', 'tofino'); ?>
                <?php echo ($data['status'] == 'publish') ? '' : '<span class="btn btn-sm btn-dark btn-disabled">' . svg('alert') . $pending_message . '</span>'; ?>
              </span>
              <?php if (can_publish_initiative($post) && !is_post_published($post)) {
                render_publish_button($post->ID);
              } ?>
            </td>
            <td>
              <a href="<?php echo add_query_arg('hub_name', $data['hub_slug'], get_the_permalink()) ?>"><?php echo $data['hub_name']; ?></a>
            </td>
            <td>
              <?php if(can_view_healthcheck($post)) {
                echo $data['latest_healthcheck'];
              } ?>
            </td>
            <td class="text-right">
              <div class="btn-group">
                <a class="btn btn-primary btn-sm" href="<?php echo $data['link']; ?>"><?php echo svg('eye'); ?><?php _e('View', 'tofino'); ?></a>
                
                <?php if(can_write_initiative($post)) { ?>
                  <?php $confirm_message = __('Are you sure you want to remove this initiative?', 'tofino'); ?>
                  <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('edit_post', $post->ID, home_url('edit-initiative')); ?>"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a>
                  <a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link($post->ID); ?>" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?><?php _e('Delete', 'tofino'); ?></a>
                <?php } ?>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <tr>
          <td><span class="total">
            <?php //echo count($post_ids) . 'items'; ?>
          </span></td>
        </tr>
    </table>
  <?php 
  } ?>

  <?php if (!$post_ids) { ?>
    <?php _e('There aren\'t any initiatives', 'tofino'); ?>
  <?php  }
  wp_reset_postdata();
}

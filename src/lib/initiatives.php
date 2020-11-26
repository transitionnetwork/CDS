<?php
function get_initiative_by_id($post_id) {
  if(false === get_transient('initiative_list_item_' . $post_id)) {
    $author_object = get_user_by('id', get_the_author_meta('ID'));
    $author_hub_name = get_the_terms($author_object, 'hub')[0]->name;
    $hub_object = wp_get_post_terms($post_id, 'hub')[0];
    $country_object = wp_get_post_terms($post_id, 'country')[0];
    $data = array(
      'link' => parse_post_link($post_id),
      'title' => get_the_title($post_id),
      'status' => get_post_status($post_id),
      'hub_slug' => $hub_object->slug,
      'hub_name' => $hub_object->name,
      'country_slug' => $country_object->slug,
      'country_name' => $country_object->name,
      'latest_healthcheck' => get_latest_healthcheck($post_id)
    );
    set_transient('initiative_list_item_' . $post_id, $data, DAY_IN_SECONDS);
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
        <?php if(!is_tax()) { ?>
          <th class="col-b"><?php _e('Hub', 'tofino'); ?></th>
        <?php } ?>
        <th class="col-b"><?php _e('Country', 'tofino'); ?></th>
        <?php if(is_user_logged_in()) { ?>
          <th class="col-b"><?php _e('Last Healthcheck', 'tofino'); ?></th>
          <th class="col-b"><?php _e('Author last login', 'tofino'); ?></th>
        <?php } ?>
        <th></th>
      </tr>
      
      <?php foreach ($post_ids as $post_id) :
        $data = get_initiative_by_id($post_id);
        $post = get_post($post_id); ?>
        <tr>
          <td>
            <a href="<?php echo $data['link']; ?>"><?php echo $data['title'] ?></a>
            <div class="status">
              <?php $pending_message = __('Pending', 'tofino'); ?>
              <?php echo ($data['status'] == 'publish') ? '' : '<span class="btn btn-sm btn-dark btn-disabled">' . svg('alert') . $pending_message . '</span>'; ?>
            </div>
            <?php if (can_publish_initiative($post) && !is_post_published($post)) {
              render_publish_button($post->ID);
            } ?>
          </td>
          <?php if(!is_tax()) { ?>
            <td>
              <a href="<?php echo get_term_link(get_term_by('slug', $data['hub_slug'], 'hub')); ?>"><?php echo $data['hub_name']; ?></a>
            </td>
          <?php } ?>
          <td>
            <a href="<?php echo add_query_arg('country_name', $data['country_slug'], get_the_permalink()) ?>"><?php echo $data['country_name']; ?></a>
          </td>
          <?php if(is_user_logged_in()) { ?>
            <td>
              <?php if(can_view_healthcheck($post)) { ?>
                <?php echo $data['latest_healthcheck']; ?>
              <?php } else { ?>
                <?php echo '-'; ?>
              <?php } ?>
            </td>
            <td>
              <?php if(can_view_healthcheck($post)) { ?>
                <?php
                $author_id = get_post_field( 'post_author', $post_id );
                $last_login = get_user_meta($author_id, 'last_logged_in', true);
                if($last_login) {
                  echo date('l jS F Y - H:i', strtotime($last_login));
                } else {
                  echo 'Never';
                } ?>
              <?php } else { ?>
                <?php echo '-'; ?>
              <?php } ?>
            </td>
          <?php } ?>
          <td class="text-right">
            <div class="btn-group">
              <?php if (can_view_healthcheck($post)) { ?>
                <a href="<?php echo add_query_arg(array('initiative_id' => $post_id), parse_post_link(422)); ?>" class="btn btn-success btn-sm"><?php echo svg('plus'); ?> Add Healthcheck</a>
              <?php } ?>

              <a class="btn btn-primary btn-sm" href="<?php echo $data['link']; ?>"><?php echo svg('eye'); ?><?php _e('View', 'tofino'); ?></a>
              
              <?php if(can_write_initiative($post)) { ?>
                <?php $confirm_message = __('Are you sure you want to remove this initiative?', 'tofino'); ?>
                <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('edit_post', $post->ID, parse_post_link(69)); ?>"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a>
                <a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link($post->ID); ?>" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?><?php _e('Delete', 'tofino'); ?></a>
              <?php } ?>
            </div>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php 
  } ?>

  <?php if (!$post_ids) { ?>
    <p><?php _e('There aren\'t any initiatives', 'tofino'); ?></p>
  <?php  }
  wp_reset_postdata();
}

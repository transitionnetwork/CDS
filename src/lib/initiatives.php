<?php
function list_initiatives($show_status = false) {
  global $posts;
  $user_role = wp_get_current_user()->roles[0];
  $user_human_role = ucwords(str_replace('_', ' ', $user_role));
  $user_hub = get_the_terms(wp_get_current_user(), 'hub');
  $user_hub_name = $user_hub[0]->name;
  $current_page = sanitize_post($GLOBALS['wp_the_query']->get_queried_object());

  if ($posts) { ?>
    <table class="item-list">
        <tr>
          <th class="col-a">Initiative</th>
          <th class="col-b">Hub</th>
          <th class="col-b">Last Healthcheck</th>
          <th class="col-c"></th>
        </tr>
        
        <?php foreach ($posts as $post) : ?>
          <?php setup_postdata($post);
          $author_object = get_user_by('id', get_the_author_meta('ID'));
          $author_hub_name = get_the_terms($author_object, 'hub')[0]->name; ?>
          <tr>
            <td>
              <a href="<?php the_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a>
              <?php if ($show_status) { ?>
                <span class="status">
                  <?php echo ($post->post_status == 'publish') ? '<span class="btn-sm btn-success">Published</span>' : '<span class="btn-sm btn-dark">Pending approval</span>'; ?>
                </span>
              <?php } ?>
            </td>
            <td>
                <?php echo (!empty($author_hub_name)) ? $author_hub_name : '-'; ?>
            </td>
            <td>
              <?php echo get_latest_healthcheck($post->ID); ?>
            </td>
            <td class="text-right">
              <a class="btn btn-primary btn-sm" href="<?php the_permalink($post->ID); ?>">View</a>

              <?php if ((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can('manage_options') || (is_super_hub_author_for_post(get_the_author_meta('ID'))))) : ?>
                <?php $params = array('edit_post' => get_the_ID()); ?>
                <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit</a>
                <a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link(get_the_ID()); ?>" onclick="return confirm('Are you sure you want to remove this hub?')">Delete</a>
              <?php endif; ?>

              <?php if(($user_role == 'super_hub') && ((get_post_status($post->ID) == 'pending'))) : ?>
                <?php show_publish_button($post->ID); ?>
              <?php endif; ?>
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
    There aren't any initiatives yet
  <?php  }
  wp_reset_postdata();
}

<?php
function list_initiatives($posts) {
  if ($posts) { ?>
    <table class="item-list">
        <tr>
          <th class="col-a">Initiative</th>
          <th class="col-b">Hub</th>
          <?php if(can_view_any_healthcheck()) { ?>
            <th class="col-b">Last Healthcheck</th>
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
                <?php echo ($post->post_status == 'publish') ? '' : '<span class="btn-sm btn-dark">Pending approval</span>'; ?>
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
              <a class="btn btn-primary btn-sm" href="<?php the_permalink($post->ID); ?>">View</a>
              
              <?php if(can_write_initiative($post)) { ?>
                <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('edit_post', $post->ID, home_url('edit-initiative')); ?>">Edit</a>
                <a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link($post->ID); ?>" onclick="return confirm('Are you sure you want to remove this hub?')">Delete</a>
              <?php } ?>
              <?php if(can_publish_initiative($post) && !is_post_published($post)) {
                show_publish_button($post->ID);
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
    There aren't any initiatives yet
  <?php  }
  wp_reset_postdata();
}

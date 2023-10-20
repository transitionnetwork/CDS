 <?php if($init_query->have_posts()) { ?>
  <table class="item-list">
    <tr>
      <th class="col-a"><?php _e('Group', 'tofino'); ?></th>
      <?php if(!is_tax()) { ?>
        <th class="col-b"><?php _e('Hub', 'tofino'); ?></th>
      <?php } ?>
      <th class="col-b"><?php _e('Country', 'tofino'); ?></th>
      <?php if(is_user_logged_in()) { ?>
        <th class="col-b"><?php _e('Last Healthcheck', 'tofino'); ?></th>
        <th class="col-b"><?php _e('Last Updated', 'tofino'); ?></th>
      <?php } ?>
      <?php if (is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
        <th class="col-b">Last Email Date</th>
      <?php } ?>
      <th></th>
    </tr>
    <?php while ($init_query->have_posts()) : $init_query->the_post(); ?>
      <?php $post = get_post($post); ?>
      <?php $country_term = get_the_terms($post, 'country')[0]; ?>
      <?php $hub_term = get_the_terms($post, 'hub')[0]; ?>
      
      <tr>
        <td>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          
          <div class="status">
            <?php $pending_message = __('Pending', 'tofino'); ?>
            <?php echo (get_post_status() === 'publish') ? '' : '<span class="btn btn-sm btn-dark btn-disabled">' . svg('alert') . $pending_message . '</span>'; ?>
          </div>
          <?php if (can_publish_initiative($post) && !is_post_published($post)) {
            render_publish_button($post);
          } ?>
        </td>
        <?php if(!is_tax()) { ?>
          <td>
            <?php if($hub_term) { ?>
              <a href="<?php echo get_term_link($hub_term); ?>"><?php echo $hub_term->name; ?></a>
            <?php } ?>
          </td>
        <?php } ?>
        <td>
          <?php if($country_term) { ?>
            <?php echo $country_term->name; ?>
          <?php } ?>
        </td>

        <?php if(is_user_logged_in()) { ?>
          <td>
            <?php if(can_view_healthcheck($post)) { ?>
              <?php echo get_latest_healthcheck($post); ?>
            <?php } else { ?>
              <?php echo '-'; ?>
            <?php } ?>
          </td>
          <td>
            <?php if(can_view_healthcheck($post)) { ?>
              <?php echo get_initiatve_age($post)['days'] . ' days'; ?>
            <?php } ?>
          </td>
        <?php } ?>
        <?php if (is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
          <td>
            <?php if(can_edit_hub($hub_term->term_id)) { ?>
              <?php echo get_post_meta($post->ID, 'last_mail_date', true); ?>
            <?php } ?>
          </td>
        <?php } ?>
        <td class="text-right">
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
              <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('edit_post', $post->ID, parse_post_link(69)); ?>"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a>

              <?php if(get_post_status($post) === 'publish') { ?>
                <?php $confirm_message = __('Are you sure you want to unpublish this group? You can re-publish it from the Dashboard', 'tofino'); ?>
                <form action="" method="post">
                  <button name="unpublish" value="<?php echo $post->ID; ?>" class="btn btn-danger btn-sm btn-last" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('x'); ?><?php _e('Unpublish', 'tofino'); ?></button>
                </form>
              <?php } ?>
            <?php } ?>
          </div>
        </td>
      </tr>
      <?php $author_requests = get_post_meta($post->ID, 'author_requests', true); ?>
      <?php if((int)$post->post_author === get_current_user_id() && $author_requests) { ?>
        <tr>
          <td colspan="100%">
            Edit access for <?php the_title(); ?> has been requested by
            <ul>
              <?php foreach($author_requests as $author_request_id) { ?>
                <li>
                  <?php $user = get_user_by( 'ID', $author_request_id ); ?>
                  <?php echo $user->display_name . ' (' . $user->user_email . ')'; ?><br/>
                  <div class="btn-group">
                    <form action="" method="post">
                      <button class="btn btn-sm btn-success" name="confirm_author_access" value="<?php echo $user->id; ?>"><?php echo svg('check'); ?>Confirm</button>
                      <button class="btn btn-sm btn-danger" name="deny_author_access" value="<?php echo $user->id; ?>"><?php echo svg('trashcan'); ?>Deny</button>
                      <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>">
                    </form>
                </li>
              <?php } ?>
            </ul>
          </td>
        </tr>
      <?php } ?>
    <?php endwhile; ?>
  </table>

  <?php 
  
  $paged = array_key_exists('paged', $init_query->query) ? $init_query->query['paged'] : null;
  $max_num_pages = $init_query->max_num_pages;
  $per_page = $init_query->query['posts_per_page'];
  $total_results = $init_query->found_posts;

  if($paged === 1 || !$paged) {
    $from = 1;
  } else {
    $from = $per_page * ($paged - 1) + 1;
  }

  if($paged < $max_num_pages) {
    $to = $per_page * $paged;
  } else {
    $to = $total_results;
  }
  
  $label = get_post_type_object($init_query->query['post_type'])->label;
  ?>
  
  <p class="mt-3"><em>Displaying <?php echo $from; ?>-<?php echo $to; ?> of <?php echo $total_results; ?>. Ordered by most recently updated.</em></p>

  <?php if($total_results > $per_page) { ?>
    <nav class="pagination" aria-label="contact-navigation">
      <?php echo paginate_links(array(
        'current' => $paged,
        'total' => $init_query->max_num_pages,
        'prev_text' => 'Prev',
        'next_text' => 'Next',
        'type' => 'list',
      )); ?>
    </nav>
  <?php } ?>


<?php } else { ?>
  <p><?php _e('No groups found', 'tofino'); ?></p>
<?php } ?>

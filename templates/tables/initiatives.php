<?php if (is_user_logged_in()) {
  $num_cols = 3;
  if(is_user_role(array('administrator', 'super_hub', 'hub'))) {
    $num_cols = 4;
  }
} else {
  $num_cols = 2;
} ?>

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
        <th class="col-b"><?php _e('Date Created', 'tofino'); ?></th>
        <th class="col-b"><?php _e('Last Email Date', 'tofino'); ?></th>
        <th class="col-b"><?php _e('Last Email Event', 'tofino'); ?></th>
      <?php } ?>
    </tr>
    <?php while ($init_query->have_posts()) : $init_query->the_post(); ?>
      <?php $post = get_post($post); ?>
      <?php $country_term = get_the_terms($post, 'country')[0]; ?>
      <?php $hub_term = get_the_terms($post, 'hub')[0]; ?>
      <?php $tags = get_group_tags($post); ?>
      
      <tr class="group-row">
        <tr>
          <td>
            <a href="<?php the_permalink(); ?>"><span class="group-title"><?php the_title(); ?></span></a>
            
            <div class="status">
              <?php $pending_message = __('Not published', 'tofino'); ?>
              <?php echo (get_post_status() === 'publish') ? '' : '<span class="btn btn-sm btn-outline btn-disabled">' . svg('alert') . $pending_message . '</span>'; ?>
            </div>
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
              <?php echo get_the_date('Y-m-d H:i:s'); ?>
            </td>
            <td>
              <?php if(can_edit_hub($hub_term->term_id)) { ?>
                <?php echo get_post_meta($post->ID, 'last_mail_date', true); ?>
              <?php } ?>
            </td>
            <td>
              <?php if(can_edit_hub($hub_term->term_id)) { ?>
                <?php echo get_post_meta($post->ID, 'last_mail_event', true); ?>
              <?php } ?>
            </td>
          <?php } ?>
        </tr>
        <tr>
          <td colspan="<?php echo $num_cols; ?>">
            <?php if($tags) { ?>
              <?php $labels = []; ?>
              <?php foreach($tags as $tag) {
                $labels[] = $tag['label'];
              } ?>
              <span class="tag-list">Tags: <?php echo implode(', ', $labels); ?></span>
            <?php } ?>
          </td>
          <td colspan="<?php echo $num_cols; ?>" class="text-right">
            <?php get_template_part('templates/partials/item-button-group'); ?>
          </td>
        </tr>
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

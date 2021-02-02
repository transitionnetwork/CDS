
<?php if($init_query->have_posts()) { ?>
  <table class="item-list">
    <tr>
      <th class="col-a"><?php _e('Initiative', 'tofino'); ?></th>
      <?php if(!is_tax()) { ?>
        <th class="col-b"><?php _e('Hub', 'tofino'); ?></th>
      <?php } ?>
      <th class="col-b"><?php _e('Country', 'tofino'); ?></th>
      <?php if(is_user_logged_in()) { ?>
        <th class="col-b"><?php _e('Last Healthcheck', 'tofino'); ?></th>
        <th class="col-b"><?php _e('Last Updated', 'tofino'); ?></th>
      <?php } ?>
      <th></th>
    </tr>
    <?php while ($init_query->have_posts()) : $init_query->the_post(); ?>
      <?php $country_term = get_the_terms($post->ID, 'country')[0]; ?>
      <?php $hub_term = get_the_terms($post->ID, 'hub')[0]; ?>
      
      <tr>
        <td>
          <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          
          <div class="status">
            <?php $pending_message = __('Pending', 'tofino'); ?>
            <?php echo (get_post_status() === 'publish') ? '' : '<span class="btn btn-sm btn-dark btn-disabled">' . svg('alert') . $pending_message . '</span>'; ?>
          </div>
          <?php if (can_publish_initiative($post) && !is_post_published($post)) {
            render_publish_button($post->ID);
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
              <?php echo get_latest_healthcheck($post->ID); ?>
            <?php } else { ?>
              <?php echo '-'; ?>
            <?php } ?>
          </td>
          <td>
            <?php if(can_view_healthcheck($post)) { ?>
              <?php echo get_initiatve_age($post->ID) . ' days ago'; ?>
            <?php } ?>
          </td>
        <?php } ?>
        <td class="text-right">
          <div class="btn-group">
            <?php if (can_view_healthcheck($post)) { ?>
              <a href="<?php echo add_query_arg(array('initiative_id' => $post->ID), parse_post_link(422)); ?>" class="btn btn-success btn-sm"><?php echo svg('plus'); ?> Add Healthcheck</a>
            <?php } ?>

            <a class="btn btn-primary btn-sm" href="<?php echo get_the_permalink(); ?>"><?php echo svg('eye'); ?><?php _e('View', 'tofino'); ?></a>
            
            <?php if(can_write_initiative($post)) { ?>
              <?php $confirm_message = __('Are you sure you want to remove this initiative?', 'tofino'); ?>
              <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('edit_post', $post->ID, parse_post_link(69)); ?>"><?php echo svg('pencil'); ?><?php _e('Edit', 'tofino'); ?></a>

              <form action="" method="post">
                <button name="unpublish" value="<?php echo $post->ID; ?>" class="btn btn-danger btn-sm" onclick="return confirm('<?php echo $confirm_message; ?>')"><?php echo svg('trashcan'); ?><?php _e('Delete', 'tofino'); ?></button>
              </form>
            <?php } ?>
          </div>
        </td>
      </tr>
    <?php endwhile; ?>
  </table>

  <?php 
  $paged = $init_query->query['paged'];
  $max_num_pages = $init_query->max_num_pages;
  $per_page = $init_query->query['posts_per_page'];
  $total_results = $init_query->found_posts;

  if($paged === 1) {
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
        'base' => @add_query_arg('paged', '%#%'),
        'format' => '?paged=%#%',
        'current' => $init_query->query['paged'],
        'total' => $init_query->max_num_pages,
        'prev_text' => 'Prev',
        'next_text' => 'Next',
        'type' => 'list',
      )); ?>
    </nav>
  <?php } ?>


<?php } else { ?>
  <p><?php _e('No initaitives found', 'tofino'); ?></p>
<?php } ?>

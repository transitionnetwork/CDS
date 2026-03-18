<?php if (is_user_logged_in()) {
  $num_cols = 3;
  if(is_user_role(array('administrator', 'super_hub', 'hub'))) {
    $num_cols = 4;
  }
} else {
  $num_cols = 2;
} ?>

<?php if($init_query->have_posts()) { ?>
  <div class="initiative-list flex flex-col gap-4">
    <?php while ($init_query->have_posts()) : $init_query->the_post(); ?>
      <?php $post = get_post($post); ?>
      <?php $country_term = (get_the_terms($post, 'country')) ? get_the_terms($post, 'country')[0] : null; ?>
      <?php $hub_term = (get_the_terms($post, 'hub')) ? get_the_terms($post, 'hub')[0] : null; ?>
      <?php $tags = get_group_tags($post); ?>

      <div class="group-card card card-border bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3">
          <?php // Title row ?>
          <div class="flex flex-wrap items-center gap-2">
            <a href="<?php the_permalink(); ?>" class="text-2xl font-bold font-header"><?php the_title(); ?></a>
            <?php if(get_post_status() !== 'publish') { ?>
              <span class="badge badge-outline badge-warning text-xs"><?php _e('Not published', 'tofino'); ?></span>
            <?php } ?>
          </div>

          <?php // Metadata badges ?>
          <div class="flex flex-wrap gap-2 text-sm">
            <?php if(!is_tax() && $hub_term) { ?>
              <a href="<?php echo get_term_link($hub_term); ?>" class="badge badge-outline badge-primary no-underline"><?php _e('Hub', 'tofino'); ?>: <?php echo $hub_term->name; ?></a>
            <?php } elseif(!is_tax()) { ?>
              <span class="badge badge-outline badge-neutral"><?php _e('Hub', 'tofino'); ?>: —</span>
            <?php } ?>

            <?php if($country_term) { ?>
              <span class="badge badge-outline badge-info"><?php _e('Country', 'tofino'); ?>: <?php echo $country_term->name; ?></span>
            <?php } ?>

            <?php if(is_user_logged_in()) { ?>
              <?php if(can_view_healthcheck($post)) { ?>
                <span class="badge badge-outline badge-secondary"><?php _e('Last Healthcheck', 'tofino'); ?>: <?php echo get_latest_healthcheck($post); ?></span>
                <span class="badge badge-outline badge-neutral"><?php _e('Updated', 'tofino'); ?>: <?php echo get_initiatve_age($post)['days'] . ' days'; ?></span>
              <?php } ?>
            <?php } ?>

            <?php if (is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
              <span class="badge badge-outline badge-neutral"><?php _e('Created', 'tofino'); ?>: <?php echo get_the_date('j-M-Y'); ?></span>
              <?php if($hub_term && can_edit_hub($hub_term->term_id)) { ?>
                <span class="badge badge-outline badge-error"><?php _e('Last Email', 'tofino'); ?>: <?php echo get_post_meta($post->ID, 'last_mail_date', true) ?: '—'; ?></span>
              <?php } ?>
              <?php if($hub_term && can_edit_hub($hub_term->term_id)) { ?>
                <span class="badge badge-outline badge-success"><?php _e('Email Event', 'tofino'); ?>: <?php echo ucwords(get_post_meta($post->ID, 'last_mail_event', true)) ?: '—'; ?></span>
              <?php } ?>
            <?php } ?>
          </div>

          <?php // Tags ?>
          <?php if($tags) { ?>
            <?php $labels = []; ?>
            <?php foreach($tags as $tag) {
              $labels[] = $tag['label'];
            } ?>
            <div class="text-sm text-gray-500">Tags: <?php echo implode(', ', $labels); ?></div>
          <?php } ?>

          <?php // Action buttons ?>
          <div>
            <?php get_template_part('templates/partials/item-button-group'); ?>
          </div>
        </div>

        <?php // Author access requests ?>
        <?php $author_requests = get_post_meta($post->ID, 'author_requests', true); ?>
        <?php if((int)$post->post_author === get_current_user_id() && $author_requests) { ?>
          <div class="mt-3 pt-3 border-t border-gray-200">
            <p class="text-sm font-semibold mb-2"><?php _e('Edit access requested by:', 'tofino'); ?></p>
            <ul class="list-none p-0 m-0">
              <?php foreach($author_requests as $author_request_id) { ?>
                <li class="mb-2">
                  <?php $user = get_user_by( 'ID', $author_request_id ); ?>
                  <span class="text-sm"><?php echo $user->display_name . ' (' . $user->user_email . ')'; ?></span>
                  <form action="" method="post" class="inline-flex gap-1 ml-2">
                    <button class="btn btn-sm btn-success" name="confirm_author_access" value="<?php echo $user->id; ?>"><?php echo svg('check'); ?>Confirm</button>
                    <button class="btn btn-sm btn-error" name="deny_author_access" value="<?php echo $user->id; ?>"><?php echo svg('trashcan'); ?>Deny</button>
                    <input type="hidden" name="post_id" value="<?php echo $post->ID; ?>">
                  </form>
                </li>
              <?php } ?>
            </ul>
          </div>
        <?php } ?>
      </div>
    <?php endwhile; ?>
  </div>

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

  <p class="mt-4"><em>Displaying <?php echo $from; ?>-<?php echo $to; ?> of <?php echo $total_results; ?>. Ordered by most recently updated.</em></p>

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

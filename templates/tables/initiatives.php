<?php if (is_user_logged_in()) {
  $num_cols = 3;
  if(is_user_role(array('administrator', 'super_hub', 'hub'))) {
    $num_cols = 4;
  }
} else {
  $num_cols = 2;
} ?>

<?php if($init_query->have_posts()) { ?>
  <div class="initiative-list grid gap-4 lg:grid-cols-2">
    <?php while ($init_query->have_posts()) : $init_query->the_post(); ?>
      <?php $post = get_post($post); ?>
      <div class="group-card card card-border bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3">
          <?php // Title row ?>
          <div class="flex flex-wrap items-center gap-2">
            <a href="<?php the_permalink(); ?>" class="text-2xl font-bold font-header"><?php the_title(); ?></a>
          </div>

          <?php get_template_part('templates/partials/initiative-badges'); ?>

          <?php // Live Projects ?>
          <?php $live_projects = get_field('group_detail_live_projects'); ?>
          <?php if($live_projects) { ?>
            <div class="text-sm text-gray-500"><?php _e('Live Projects', 'tofino'); ?>: <?php
              $project_labels = [];
              foreach($live_projects as $item) { $project_labels[] = $item['label']; }
              echo implode(', ', $project_labels);
            ?></div>
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

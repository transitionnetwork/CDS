<?php if(is_user_role('hub')) {
  $hub_ids[] = get_field('hub_user', wp_get_current_user());
} else {
  $hubs = get_terms('hub', array(
    'hide_empty' => false
  ));
  foreach($hubs as $hub) {
    $hub_ids[] = $hub->term_id;
  }
} ?>


<section>
  <h2>Hub Admin</h2>
  <?php if ($hub_ids) { ?>
    <div class="flex flex-col gap-4">
      <?php foreach ($hub_ids as $hub_id) {
        $hub = get_term_by('id', $hub_id, 'hub'); ?>
        <div class="card card-border bg-white p-4 shadow-sm">
          <div class="flex flex-col gap-3">
            <a href="<?php echo get_term_link($hub); ?>" class="text-lg font-bold"><?php echo $hub->name; ?></a>

            <div class="flex flex-wrap gap-2">
              <a class="btn btn-primary btn-sm" href="<?php echo add_query_arg('hub_id', $hub->term_id, get_the_permalink(6105)); ?>"><?php echo svg('cloud-download'); ?>CSV of group data</a>
              <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('hub_id', $hub->term_id, get_the_permalink(5414)); ?>"><?php echo svg('pencil'); ?>Edit</a>
            </div>

            <?php if(is_user_role(array('super_hub', 'administrator'))) {
              $args = array(
                'meta_key' => 'hub_admin_requested',
                'meta_value' => $hub_id,
                'meta_compare' => 'EXISTS'
              );
              $user_query = new WP_User_Query($args);
              if (!empty($user_query->get_results())) { ?>
                <div class="mt-2 pt-3 border-t border-gray-200">
                  <p class="text-sm font-semibold mb-2"><?php _e('Users awaiting approval:', 'tofino'); ?></p>
                  <ul class="list-none p-0 m-0">
                    <?php foreach ($user_query->get_results() as $user) { ?>
                      <li class="mb-2">
                        <span class="text-sm"><?php echo $user->display_name . ' (' . $user->user_email . ')'; ?></span>
                        <form action="" method="post" class="inline-flex gap-1 ml-2">
                          <button class="btn btn-sm btn-success" name="confirm_hub_admin" value="<?php echo $user->id; ?>"><?php echo svg('check'); ?>Confirm</button>
                          <button class="btn btn-sm btn-error" name="deny_hub_admin" value="<?php echo $user->id; ?>"><?php echo svg('trashcan'); ?>Deny</button>
                          <input type="hidden" name="hub_id" value="<?php echo $hub_id; ?>">
                        </form>
                      </li>
                    <?php } ?>
                  </ul>
                </div>
              <?php }
            } ?>
          </div>
        </div>
      <?php } ?>
    </div>
  <?php } ?>

</section>

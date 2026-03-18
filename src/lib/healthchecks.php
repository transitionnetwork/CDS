<?php
function list_healthchecks($healthchecks) {
  if($healthchecks) { ?>
    <div class="flex flex-col gap-3">
      <?php foreach ($healthchecks as $healthcheck) :?>
        <div class="card card-border bg-white p-4 shadow-sm">
          <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
              <span class="font-semibold"><?php echo date('l jS F Y - H:i', strtotime($healthcheck->post_date)); ?></span>
              <?php if(get_post_meta($healthcheck->ID, 'incomplete')) { ?>
                <span class="badge badge-outline badge-warning text-xs ml-2">Incomplete</span>
              <?php } ?>
            </div>
            <div>
              <?php if(get_post_meta($healthcheck->ID, 'incomplete')) { ?>
                <?php $params = array(
                  'initiative_id' => $healthcheck->post_title,
                  'post_id' => $healthcheck->ID,
                  'token' => get_post_meta($healthcheck->ID, 'secret_token')[0]
                ); ?>
                <a class="btn btn-sm btn-secondary" href="<?php echo add_query_arg($params, get_the_permalink(422)); ?>"><?php echo svg('play'); ?>Resume</a>
              <?php } else { ?>
                <a class="btn btn-sm btn-primary" href="<?php echo get_the_permalink($healthcheck->ID); ?>"><?php echo svg('eye'); ?>View</a>
              <?php } ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php } else { ?>
    <p><?php _e('There aren\'t any healthchecks yet', 'tofino'); ?></p>
  <?php  }
}

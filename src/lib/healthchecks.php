<?php
function list_healthchecks($healthchecks) {
  if($healthchecks) { ?>
    <table class="item-list">
      <tr>
        <th>Healthcheck Date</th>
        <th></th>
      </tr>
      <?php foreach ($healthchecks as $healthcheck) :?>
        <tr>
          <td><?php echo date('l jS F Y - H:i', strtotime($healthcheck->post_date)); ?>
          <?php if(get_post_meta($healthcheck->ID, 'incomplete')) echo '<em>Incomplete</em>'; ?></td>
          <td class="text-right">
            <?php if(get_post_meta($healthcheck->ID, 'incomplete')) { ?>
              <?php $params = array(
                'initiative_id' => $healthcheck->post_title,
                'post_id' => $healthcheck->ID,
                'token' => get_post_meta($healthcheck->ID, 'secret_token')[0]
              ); ?>
              <a class="btn btn-sm btn-secondary" href="<?php echo add_query_arg($params, parse_post_link(422)); ?>"><?php echo svg('play'); ?>Resume</a>
            <?php } else { ?>
              <a class="btn btn-sm btn-primary" href="<?php echo parse_post_link($healthcheck->ID); ?>"><?php echo svg('eye'); ?>View</a>
            <?php } ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php } else { ?>
    <p><?php _e('There aren\'t any healthchecks yet', 'tofino'); ?></p>
  <?php  }
}

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
          <td><?php echo date('l jS F Y - H:i', strtotime($healthcheck->post_date)); ?></td>
          <td><a class="btn btn-sm btn-primary" href="<?php echo get_the_permalink($healthcheck->ID); ?>">View</td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php } else { ?>
    <p>There aren't any healhtchecks yet</p>
  <?php  }
}

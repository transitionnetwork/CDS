<?php if(is_user_role('hub')) {
  $hub_ids = get_field('hub_user', wp_get_current_user());
  if(!is_array($hub_ids)) {
    $hub_ids[] = $hub_ids;
  }
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
    <table class="item-list">
      <tr>
        <th>Hub Name</th>
        <th>Download</th>
        <th>Edit</th>
        <?php if(is_user_role(array('super_hub', 'administrator'))) { ?>
          <th>Users awaiting approval</th>
        <?php } ?>
      </tr>
      <?php foreach ($hub_ids as $hub_id) {
        $hub = get_term_by('id', $hub_id, 'hub'); ?>
        <tr>
          <td>
            <?php echo $hub->name; ?>
          </td>
          <td>
            <a class="btn btn-primary btn-sm" href="<?php echo add_query_arg('hub_id', $hub->term_id, parse_post_link(6105)); ?>"><?php echo svg('cloud-download'); ?>CSV of group data</a>
          </td>
          <td>
            <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('hub_id', $hub->term_id, parse_post_link(5414)); ?>"><?php echo svg('pencil'); ?>Edit</a>
          </td>
          
          <?php $args = array(
            'meta_key' => 'hub_admin_requested',
            'meta_value' => $hub_id,
            'meta_compare' => 'EXISTS'
          ); ?>
          <td>
            <ul>
              <?php $user_query = new WP_User_Query( $args );
              if ( ! empty( $user_query->get_results() ) ) {
                foreach ( $user_query->get_results() as $user ) { ?>
                  <li>
                    <?php echo $user->display_name . ' (' . $user->user_email . ')'; ?><br/>
                    <div class="btn-group">
                      <form action="" method="post">
                        <button class="btn btn-sm btn-success" name="confirm_hub_admin" value="<?php echo $user->id; ?>"><?php echo svg('check'); ?>Confirm</button>
                        <button class="btn btn-sm btn-danger" name="deny_hub_admin" value="<?php echo $user->id; ?>"><?php echo svg('trashcan'); ?>Deny</button>
                        <input type="hidden" name="hub_id" value="<?php echo $hub_id; ?>">
                      </form>
                  </li>
                <?php } ?>
              <?php } ?>
            </ul>
          </td>
        </tr>
      <?php } ?>
    </table>
  <?php } ?>

</section>

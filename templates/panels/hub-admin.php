<section>
  <h2>Hub Admin</h2>
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


  <?php if ($hub_ids) { ?>
    <div class="row">
      <div class="col-12 col-lg-6">
        <table class="item-list">
          <tr>
            <th>Hub Name</th>
            <th></th>
            <th></th>
          </tr>
          <?php foreach ($hub_ids as $hub_id) {
            $hub = get_term_by('id', $hub_id, 'hub'); ?>
            <tr>
              <td>
                <?php echo $hub->name; ?>
              </td>
              <td class="text-right">
                <a class="btn btn-primary btn-sm" href="<?php echo add_query_arg('hub_id', $hub->term_id, parse_post_link(6105)); ?>"><?php echo svg('cloud-download'); ?>CSV of initiative data</a>
              </td>
              <td class="text-right">
                <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg('hub_id', $hub->term_id, parse_post_link(5414)); ?>"><?php echo svg('pencil'); ?>Edit</a>
              </td>
            </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  <?php } ?>
</section>

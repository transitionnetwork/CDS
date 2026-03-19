<?php $hubs = get_the_terms($post, 'hub'); ?>

<div class="panel-dash">
  <?php $logo = get_field('logo'); ?>
  <?php if($logo) { ?>
    <div class="mb-4">
      <img src="<?php echo get_logo('logo', $post); ?>">
    </div>
  <?php } ?>

  <?php $detail = get_field('group_detail'); ?>
  <?php if($detail) { ?>
    <?php
    $simple_fields = array(
      'number_of_people' => 'Members',
      'number_of_participants' => 'Active last year',
      'date' => 'Started',
      'paid_roles' => 'Paid roles',
      'legal_structure' => 'Legal structure',
      'active' => 'Activity',
    );

    foreach($simple_fields as $key => $label) {
      $acf_value = get_field('group_detail_' . $key);
      if($acf_value) { ?>
        <div class="info-row">
          <span class="info-label"><?php echo $label; ?></span>
          <span class="info-value"><?php echo $acf_value; ?></span>
        </div>
      <?php } ?>
    <?php } ?>

    <?php $more_info = get_field('group_detail_number_more_info'); ?>
    <?php if($more_info) { ?>
      <div class="col-span-full mt-1 text-sm text-gray-600"><?php echo $more_info; ?></div>
    <?php } ?>

    <?php $legal_detail = get_field('group_detail_legal_structure_detail'); ?>
    <?php if($legal_detail) { ?>
      <div class="col-span-full mt-1 text-sm text-gray-500"><?php echo $legal_detail; ?></div>
    <?php } ?>

    <?php $live_projects = get_field('group_detail_live_projects'); ?>
    <?php if($live_projects) { ?>
      <div class="col-span-full pt-2">
        <span class="info-label"><?php _e('Live Projects', 'tofino'); ?></span>
        <div class="flex flex-wrap gap-1.5 mt-1">
          <?php foreach($live_projects as $item) { ?>
            <span class="badge badge-info badge-sm"><?php echo $item['label']; ?></span>
          <?php } ?>
        </div>
      </div>
    <?php } ?>

    <?php $projects_detail = get_field('group_detail_live_projects_detail'); ?>
    <?php if($projects_detail) { ?>
      <div class="col-span-full mt-1 text-sm text-gray-500"><?php echo $projects_detail; ?></div>
    <?php } ?>

  <?php } ?>
</div>

<?php $hubs = get_the_terms($post, 'hub'); ?>
<?php $topics = get_the_terms($post, 'topic'); ?>

<div class="panel">
  <?php $logo = get_field('logo'); ?>
  <?php if($logo) { ?>
    <div class="mb-3">
      <?php if(is_array($logo)) { ?>
        <img src="<?php echo get_field('logo')['sizes']['large']; ?>">
      <?php } else { ?>
        <?php echo wp_get_attachment_image( $logo, 'large' ); ?>
      <?php } ?>
    </div>
  <?php } ?>

  <?php if(count($hubs) === 1) { ?>
    <div class="mb-3">
      <label>Hub</label>
      <a href="<?php echo get_term_link($hubs[0]); ?>"><?php echo $hubs[0]->name; ?></a>
    </div>
  <?php } ?>
  
  <?php $detail = get_field('group_detail'); ?>
  <?php if($detail) { ?>
    <?php $fields = array(
      'number_of_people' => 'Number of people in group:',
      'number_of_participants' =>  'Active participants in last year:',
      'number_more_info' => null,
      'date' => 'Group started:',
      'paid_roles' => 'Paid roles?',
      'legal_structure' => 'Legal Structure:',
      'legal_structure_detail' => null,
      'active' => 'How active is your group?',
      'live_projects' => 'Live Projects:',
      'live_projects_detail' => null
    );

    foreach($fields as $key => $label) {
      $acf_value = get_field('group_detail_' . $key);
      if($acf_value) {
        if($fields[$key]) { ?>
          <label><?php echo $fields[$key]; ?></label>
        <?php } ?>

        <div>
          <?php if(is_array($acf_value)) { ?>
            <ul>
              <?php foreach($acf_value as $item) { ?>
                <li><?php echo $item; ?></li>
              <?php } ?>
            </ul>
          <?php } else {
            echo $acf_value;
          } ?>
        </div>
      <?php } ?>
    <?php } ?>
    
  <?php } ?>
</div>



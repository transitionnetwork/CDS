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
      <h3>Hub</h3>
      <a href="<?php echo get_term_link($hubs[0]); ?>"><?php echo $hubs[0]->name; ?></a>
    </div>
  <?php } ?>
  
  <?php if($topics) { ?>
    <?php $topic_names = array(); ?>
    <?php foreach($topics as $topic) {
      $topic_names[] = $topic->name;
    } ?>
    
    <div class="mb-3">
      <h3>Topics</h3>
      <?php echo implode(', ', $topic_names); ?>
    </div>
  <?php } ?>
  
  <?php $detail = get_field('group_detail'); ?>
  <?php if($detail) { ?>
    <?php $number_of_people = get_field('group_detail_number_of_people');
    if($number_of_people) { ?>
      <div class="mb-3">
        <h3>
          Number of People organising and running group & projects
        </h3>
        <?php echo $number_of_people; ?>
      </div>
    <?php } ?>

    <?php $number_of_participants = get_field('group_detail_number_of_participants');
    if($number_of_participants) { ?>
      <div class="mb-3">
        <h3>
          Number of participants in last year
        </h3>
        <?php echo $number_of_participants; ?>
      </div>
    <?php } ?>

    <?php $number_more_info = get_field('group_detail_number_more_info');
    if($number_more_info) { ?>
      <div class="mb-3">
        <?php echo $number_more_info; ?>
      </div>
    <?php } ?>
    
    <?php $group_date = get_field('group_detail_date');
    if($group_date) { ?>
        <h3 class="mt-3">
          Group founded
        </h3>
        <?php echo $group_date; ?>
    <?php } ?>
    
    <?php $paid_roles = get_field('group_detail_paid_roles'); ?>
    <?php if($paid_roles) { ?>
      <h3 class="mt-3">
        Paid Roles?
      </h3>
      <?php echo $paid_roles; ?>
    <?php } ?>
  
    <?php $legal_structure = get_field('group_detail_legal_structure'); ?>
    <?php if($legal_structure) { ?>
      <h3 class="mt-3">
        Legal Structure
      </h3>
      <div>
        <?php echo $legal_structure; ?>
      </div>
      <?php if($legal_structure === 'Other' && get_field('group_detail_legal_structure_detail')) { ?>
        <div class="mt-3">
          <?php echo get_field('group_detail_legal_structure_detail'); ?>
        </div>
      <?php } ?>
    <?php } ?>
      
    <?php $group_activity = get_field('group_detail_active'); ?>
    <?php if($group_activity) { ?>
      <h3 class="mt-3">
        Group Activity
      </h3>
      <?php echo $group_activity; ?>
    <?php } ?>
    
    <?php $live_projects = get_field('group_detail_live_projects'); ?>
    <?php if($live_projects) { ?>
      <h3 class="mt-3">
        Live Projects
      </h3>
      <?php $project_list = array(); ?>
      <?php $other_project_selected = false; ?>
      <?php foreach($live_projects as $live_project) { ?>
        <?php $project_list[] = $live_project; ?>
        <?php if($live_project === 'Other') { 
          $other_project_selected = true; ?>
        <?php } ?>
      <?php } ?>
      <div>
        <?php echo implode(', ', $project_list); ?>
      </div>
      <?php if($other_project_selected && get_field('group_detail_live_projects_detail') && is_user_logged_in() && is_user_role(array('super_hub', 'administrator'))) { ?>
        <div class="mt-3">
          <?php echo get_field('group_detail_live_projects_detail'); ?>
        </div>
      <?php } ?>
    <?php } ?>
  <?php } ?>
</div>



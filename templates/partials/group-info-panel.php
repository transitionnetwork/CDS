<?php $email = get_field('email'); ?>
<?php $additional = get_field('additional_web_addresses'); ?>
<?php
$link_fields = array(
  'website',
  'twitter',
  'facebook',
  'instagram',
  'youtube',
);

$has_links = false;

foreach($link_fields as $field) {
  if(get_field($field)) {
    $has_links = true;
  }
} ?>

<?php if($email || $has_links || $additional) { ?>
  <div class="panel">
    <?php if ($email) { ?>
      <h3>Email</h3>
      <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
    <?php } ?>
  
    <?php if($has_links) { ?>
      <div>
        <h3 class="mt-3"><?php _e('Links', 'tofino'); ?></h3>
        
        <ul class="links">
          <?php foreach($link_fields as $field) {
            if(get_field($field)) { ?>
              <li><a href="<?php echo get_field($field); ?>" target="_blank"><?php echo svg($field); ?></a></li>
            <?php } ?>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
    
    <?php if($additional) { ?>
      <div>
        <h3 class="mt-3"><?php _e('More Links', 'tofino'); ?></h3>
        <ul>
          <?php foreach($additional as $item) { ?>
            <li><a href="<?php echo $item['address']; ?>" target="_blank"><?php echo $item['label']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
    <?php } ?>
  </div>
<?php } ?>

<?php $hubs = get_the_terms($post, 'hub'); ?>
<?php $topics = get_the_terms($post, 'topic'); ?>

<div class="panel">
  <?php if(get_field('logo')) { ?>
    <img src="<?php echo get_field('logo')['sizes']['large']; ?>">
  <?php } ?>

  <?php if(count($hubs) === 1) { ?>
    <h3>Hub</h3>
    <a href="<?php echo get_term_link($hubs[0]); ?>"><?php echo $hubs[0]->name; ?></a>
  <?php } ?>
  
  <?php if($topics) { ?>
    <?php $topic_names = array(); ?>
    <?php foreach($topics as $topic) {
      $topic_names[] = $topic->name;
    } ?>
    
    <h3 class="mt-3">Topics</h3>
    <?php echo implode(', ', $topic_names); ?>
  <?php } ?>
  
  <?php $detail = get_field('group_detail'); ?>
  <?php if($detail) { ?>
    <?php $number_of_people = get_field('group_detail_number_of_people');
    if($number_of_people) { ?>
        <h3 class="mt-3">
          Number of People
        </h3>
        <?php echo $number_of_people; ?>
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
      <?php echo $legal_structure; ?>
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
      <?php foreach($live_projects as $live_project) { ?>
        <?php $project_list[] = $live_project; ?>
      <?php } ?>
      <?php echo implode(', ', $project_list); ?>
    <?php } ?>
  <?php } ?>
</div>


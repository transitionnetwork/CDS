<?php
$args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
);

if(!is_front_page()) {
  $term = get_queried_object();
  $args['tax_query'] = array(
    array(
      'taxonomy' => 'hub',
      'field' => 'term_id',
      'terms' => $term->term_id
    )
  );
} 

$groups = get_posts($args);

$total_groups = count($groups);
$active_groups = 0;
$count_number_of_people = 0;
$count_number_of_participants = 0;

$recent_day_count = (get_field('recent_day_count', 'options')) ? (get_field('recent_day_count', 'options')) : 365;

$date_one_year_past = new DateTime('-' . $recent_day_count . 'days');

$count_projects = array();
$total_projects = 0;

foreach($groups as $group) {
  if(strtotime($group->post_modified) > $date_one_year_past->format('U')) {
    $active_groups ++;
  }

  $number_of_people = get_field('group_detail_number_of_people', $group);
  if($number_of_people) { 
    $count_number_of_people += (int)$number_of_people;
  }
  
  $number_of_participants = get_field('group_detail_number_of_participants', $group);
  if($number_of_participants) { 
    $count_number_of_participants += $number_of_participants;
  }

  $live_projects = get_field('group_detail_live_projects', $group);
  if($live_projects) {
    foreach($live_projects as $project) {
      if(array_key_exists($project, $count_projects)) {
        $count_projects[$project] = $count_projects[$project] + 1; 
      } else {
        $count_projects[$project] = 0; 
      }
      $total_projects ++;
    }
  }
} ?>

<?php if($groups) { ?>
  <div class="panel">
    <p>
      <strong><?php echo $active_groups; ?></strong> out of <strong><?php echo $total_groups; ?></strong> groups are recently active on this site (<strong><?php echo round($active_groups / $total_groups * 100, 1); ?></strong>%</strong>).
    </p>
    <?php if($count_number_of_people) { ?>
      <p>
        More than <strong><?php echo $count_number_of_people; ?></strong> people are participating in Transition organisation.
      </p>
    <?php } ?>
    <?php if($count_number_of_participants) { ?>
      <p>
        More than <strong><?php echo $count_number_of_participants; ?></strong> people have been reached with this work.
      </p>
    <?php } ?>

    <?php if(!empty($count_projects)) { ?>
      <?php arsort($count_projects); ?>
      <p>
        <label>Group projects include:</label>
        <ul>
          <?php foreach($count_projects as $project => $count) { ?>
            <?php if($count > 1) { ?>
              <li><?php echo $project; ?>: <strong><?php echo $count; ?></strong> (<?php echo round($count / $total_projects * 100); ?>%)</li>
            <?php } ?>
          <?php } ?>
        </ul>
      </p>
    <?php } ?>
  </div>
<?php } ?>

<?php wp_reset_postdata(); ?>

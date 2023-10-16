<?php $term = get_queried_object(); ?>
<?php $args = array(
  'post_type' => 'initiatives',
  'posts_per_page' => -1,
  'tax_query' => array(
    array(
      'taxonomy' => 'hub',
      'field' => 'term_id',
      'terms' => $term->term_id
    )
  )
);

$posts = get_posts($args);

$total_groups = count($posts);
$active_groups = 0;
$count_number_of_people = 0;
$count_number_of_participants = 0;

$date_one_year_past = new DateTime("-312 days");
foreach($posts as $post) {
  if(strtotime($post->post_modified) > $date_one_year_past->format('U')) {
    $active_groups ++;
  }

  $number_of_people = get_field('group_detail_number_of_people', $post);
  if($number_of_people) { 
    $count_number_of_people += (int)$number_of_people;
  }
  
  $number_of_participants = get_field('group_detail_number_of_participants', $post);
  if($number_of_participants) { 
    $count_number_of_participants += $number_of_participants;
  }
} ?>

<?php if($posts) { ?>
  <div class="panel">
    <h3>In the last 12 months:</h3>
    <div>
      <strong><?php echo $active_groups; ?></strong> groups are active on this site
    </div>
    <div>
      from <strong><?php echo $total_groups; ?></strong> total groups
    </div>
    <div>
      <strong><?php echo round($active_groups / $total_groups * 100, 1); ?></strong>% of all groups  
    </div>
    <?php if($count_number_of_people) { ?>
      <label>People participating in transition organising</label>
      At least <strong><?php echo $count_number_of_people; ?></strong> people
    <?php } ?>
    <?php if($count_number_of_participants) { ?>
      <label>People reached with this work</label>
      At least <strong><?php echo $count_number_of_participants; ?></strong> people
    <?php } ?>
  </div>
<?php } ?>

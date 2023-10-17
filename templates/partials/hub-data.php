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

$recent_day_count = (get_field('recent_day_count', 'options')) ? (get_field('recent_day_count', 'options')) : 365;

$date_one_year_past = new DateTime('-' . $recent_day_count . 'days');
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
  </div>
<?php } ?>

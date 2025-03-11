<?php
$data = get_group_totals($args);
?>

<div class="panel">
  <?php if($args['view'] === 'list') { ?>
    <p>Groups are active in <strong><?php $data['total_countries']; ?> countries</strong></p>
  <?php } ?>
  
  <p>
    <strong><?php echo $data['total_active_groups']; ?></strong> out of <strong><?php echo $data['total_groups']; ?></strong> groups are recently active on this site (<strong><?php echo round($data['total_active_groups'] / $data['total_groups'] * 100, 1); ?></strong>%</strong>).
  </p>
  <?php if($data['total_people']) { ?>
    <p>
      More than <strong><?php echo $data['total_people']; ?></strong> people are participating in Transition organisation.
    </p>
  <?php } ?>
  <?php if($data['total_participants']) { ?>
    <p>
      More than <strong><?php echo $data['total_participants']; ?></strong> people have been reached with this work.
    </p>
  <?php } ?>

  <?php $projects = $data['projects']; ?>
  <?php if(!empty($projects)) { ?>
    <?php $array_project_count = array_column($projects, 'number_of_projects'); ?>
    <?php array_multisort($array_project_count, SORT_DESC, $projects); ?>
    <p>
      <label>Group projects include:</label>
      <ul>
        <?php foreach($projects as $project) { ?>
          <?php if($project['number_of_projects'] > 1) { ?>
            <li><?php echo $project['name']; ?>: <strong><?php echo $project['number_of_projects']; ?></strong> (<?php echo round($project['number_of_projects'] / $data['total_projects'] * 100); ?>%)</li>
          <?php } ?>
        <?php } ?>
      </ul>
    </p>
  <?php } ?>
  <p><a href="https://knowledge.transition-space.org/books/transitiongroupsorg/page/hub-annual-activity-checker-email-process" target="_blank">About these numbers</a></p>
</div>

<?php wp_reset_postdata(); ?>

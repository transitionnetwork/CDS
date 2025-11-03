<?php
function get_group_totals($args) {
  $query_args = array(
    'post_type' => 'initiatives',
    'posts_per_page' => -1,
  );

  if($args['view'] !== 'list') {
    $term = get_queried_object();
    $query_args['tax_query'] = array(
      array(
        'taxonomy' => 'hub',
        'field' => 'term_id',
        'terms' => $term->term_id
      )
    );
  } 

  $groups = get_posts($query_args);

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
        if(array_key_exists($project['value'], $count_projects)) {
          $count_projects[$project['value']]['number_of_projects'] ++; 
        } else {
          $count_projects[$project['value']]['name'] = $project['label'];
          $count_projects[$project['value']]['number_of_projects'] = 0; 
        }
        $total_projects ++;
      }
    }
  }

  $countries = get_terms(array(
    'taxonomy' => 'country',
    'hide_empty' => true,
    'post_type' => 'initiatives'
  ));

  $data = array(
    'total_countries' => count($countries),
    'total_active_groups' => $active_groups,
    'total_groups' => $total_groups,
    'total_people' => $count_number_of_people,
    'total_participants' => $count_number_of_participants,
    'total_projects' => $total_projects
  );
  
  if(!empty($count_projects)) {
    foreach($count_projects as $project => $info) {
      if($info['number_of_projects'] > 0) {
        $data['projects'][] = array(
          'name' => $info['name'],
          'slug' => $project,
          'number_of_projects' => $info['number_of_projects']
        );
      }
    }

    $count = array_column($data['projects'], 'number_of_projects');
    array_multisort($count, SORT_DESC, $data['projects']);
  }


  return $data;
}

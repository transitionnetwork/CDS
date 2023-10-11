<section>

  <?php $response_map = array(
      1 => 'Strongly Disagree',
      2 => 'Disagree',
      3 => 'Mostly Agree',
      4 => 'Agree',
      5 => 'Strongly Agree',
  ); ?>

  <?php
  $args = array(
    'post_type' => 'healthchecks',
    'posts_per_page' => -1,
    'meta_query' => array(
      array(
        'key' => 'incomplete',
        'compare' => 'NOT EXISTS'
      )
    )
  );

  $posts = get_posts($args);
  ?>

  <?php if($posts) {
    if(is_user_role(array('super_hub', 'hub'))) {
      $user_hub_id = get_field('hub_user', wp_get_current_user());
      $user_hub_object = get_term_by('term_id', $user_hub_id, 'hub');
      
      $tab_title = 'Mean Healthcheck Data: ' . $user_hub_object->name;

      foreach($posts as $key => $post) {
        //clean all non hub results from posts array
        $group_id = (int)$post->post_title;
        $hub_id = get_the_terms($group_id, 'hub')[0]->term_id;

        if($user_hub_id !== $hub_id) {
          unset($posts[$key]);
        }
      }
    } else {
      $tab_title = 'Mean Healthcheck Data: All hubs';
    }

    echo '<h2 class="mb-3">' . $tab_title . '</h2>';
    
    $count_results = count($posts);
    
    //reindex array
    $posts = array_values($posts);

    //TODO: clean all duplicates from array (most recent ones stay)

    foreach($posts as $post) {
      // get associate initiave post object
      $initiative = get_post($post->post_title);
      
      if($initiative->post_status == 'publish') {
        $group_i = 0;

        $questions = array();

        $fields = get_field_objects($post->ID);
        if($fields) {
          foreach($fields as $group) {
            $questions[$group_i]['group_label'] = $group['label'];
            
            $question_i = 0;
            
            foreach($group['sub_fields'] as $question_i => $sub_field) {
              $questions[$group_i][$question_i]['field_label'] = $sub_field['label'];
              $questions[$group_i][$question_i]['field_name'] = $group['name'] . '_' . $sub_field['name'];

              $question_i ++;
            }

            $group_i ++;
          }
        }
        
      }
      break;
    }


    foreach($posts as $post) {
      foreach($questions as $group) {
        foreach($group as $key => $item) {
          if(is_int($key)) {
            $output_answers[$item['field_name']] += get_field($item['field_name']);
          }
        }
      }
    }
  } ?>

  <?php foreach($questions as $key => $group) { ?>
    <div class="panel">
      <h3><?php echo $key + 1 . '. ' . $group['group_label']; ?></h3>

      <div class="panel">
        <?php foreach($group as $key => $item) {
            if(is_int($key)) { ?>
              <?php $average = (int)$output_answers[$item['field_name']] / $count_results; ?>
              
              <div class="mb-3">
                <div class="mb-1">
                  <?php echo $key + 1 . '. ' . $item['field_label']; ?>
                </div>
                <span class="healthcheck-response response-<?php echo round($average); ?>">
                  <?php echo $response_map[round($average)]; ?> [<?php echo round($average) ?>]
                </span>
              </div>
            <?php }
          } ?>
      </div>
      
    </div>
  <?php } ?>

</section>

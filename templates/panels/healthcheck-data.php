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
        $terms = get_the_terms($group_id, 'hub');
        
        if($terms && !is_wp_error($terms) && isset($terms[0])) {
          $hub_id = $terms[0]->term_id;
          
          if($user_hub_id !== $hub_id) {
            unset($posts[$key]);
          }
        } else {
          // Remove posts without valid hub terms
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

    $output_answers = array();
    foreach($posts as $post) {
      foreach($questions as $group) {
        foreach($group as $key => $item) {
          if(is_int($key)) {
            // Initialize array key if not exists
            if(!isset($output_answers[$item['field_name']])) {
              $output_answers[$item['field_name']] = 0;
            }
            
            $field_value = get_field($item['field_name']);
            $output_answers[$item['field_name']] += $field_value ? (int)$field_value : 0;
          }
        }
      }
    }
  } ?>

  <?php $alphas = range('A', 'Z'); ?>
  <?php foreach($questions as $key => $group) { ?>
    <div class="panel">
      <h3><?php echo $alphas[$key] . '. ' . $group['group_label']; ?></h3>

      <div class="panel">
        <?php foreach($group as $key => $item) {
            if(is_int($key)) { ?>
              <?php 
              $field_total = isset($output_answers[$item['field_name']]) ? (int)$output_answers[$item['field_name']] : 0;
              $average = $count_results > 0 ? $field_total / $count_results : 0;
              $rounded_average = round($average);
              ?>
              
              <div class="item healthcheck-choice choice-<?php echo $rounded_average; ?>">
                <div class="mb-1">
                  <?php echo $key + 1 . '. ' . $item['field_label']; ?>
                </div>
                <div>
                  <span class="response">
                    <?php 
                    $response_text = isset($response_map[$rounded_average]) ? $response_map[$rounded_average] : 'No Response';
                    echo $response_text . ' [' . $rounded_average . ']';
                    ?>
                  </span>
                </div>
              </div>
            <?php }
          } ?>
      </div>
      
    </div>
  <?php } ?>

</section>

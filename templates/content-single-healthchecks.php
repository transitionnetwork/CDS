<?php while (have_posts()) : the_post(); ?>
  <?php
  $initiative_id = get_the_title($post);
  $post = get_post($initiative_id);
  setup_postdata($post);
  if(!is_user_logged_in() || (!can_view_healthcheck($post))) {
    wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
    exit;
  } else { ?>
    <?php wp_reset_postdata(); ?>
    <main>
      <div class="container">
        <div class="row">
          <div class="col-12">
              <div class="header">
                <h1><?php _e('Healthcheck', 'tofino'); ?>: <a href="<?php echo parse_post_link($initiative_id); ?>"><?php echo get_the_title($initiative_id); ?></a></h1>
                <div class="date">
                  Submitted on <?php echo date('l jS F Y'); ?>
                </div>
              </div>

              <h2><?php _e('Summary', 'tofino'); ?></h2>
              <div id="healthcheck-bar"></div>
                <div id="graph-loading-wrapper">
                <div class="graph-loading"><div class="lds-dual-ring"></div></div>
              </div>

              <?php
              $alphas = range('A', 'Z');
              $fields = get_field_objects();

              if ($fields) {
                $alpha_key = 0;
                foreach ($fields as $field_name => $field) {
                  //var_dump($field);
                  if($field['type'] == 'group') { ?>
                    <div class="panel">
                      <?php $group = $field['name'];
                      $sub_fields = $field['sub_fields'];
                      echo '<h3>' . $alphas[$alpha_key] . '. ' . $field['label'] . '</h3>';
                      foreach ($sub_fields as $key => $sub_field) {
                        if($sub_field['type'] == 'button_group') {
                          $question_num = $key + 1;
                          $choice = get_field($group . '_' . $sub_field['name']);
                          
                          echo '<div class="item choice-' . $choice . '">';
                          echo '<div><label>' . $question_num . ': '. $sub_field['label'].'</label></div>';
                          echo '<div><span class="response">' . $sub_field['choices'][$choice] . '</span></div>';
                          echo '</div>';
                        }
                      } ?>
                    </div>
                    <?php $alpha_key ++; ?>
                  <?php }
                }
              } ?>
          </div>
        </div>
      </div>
    </main>
  <?php } ?>
<?php endwhile;  ?>

<main>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <?php while (have_posts()) : the_post(); ?>
          <?php $initiative_id = get_the_title(); ?>
          <h1>Healthcheck: <?php echo get_the_title($initiative_id); ?></h1>
          <h4><?php echo date('l jS F Y'); ?></h4>
          <?php $fields = get_field_objects();

          if ($fields) {
            foreach ($fields as $field_name => $field) {
              //var_dump($field);
              if($field['type'] == 'group') {
                $group = $field['name'];
                $sub_fields = $field['sub_fields'];
                echo '<h3>' . $field['label'] . '</h3>';
                foreach ($sub_fields as $sub_field) {
                  if($sub_field['type'] == 'button_group') {
                    $choice = get_field($group . '_' . $sub_field['name']);
                    
                    echo '<div class="item choice-' . $choice . '">';
                    echo '<label>'.$sub_field['label'].'</label>';
                    echo '<span>' . $sub_field['choices'][$choice] . '</span>';
                    echo '</div>';
                  }
                }
              }
            }
          }

          ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>
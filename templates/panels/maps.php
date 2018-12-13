<section>
      <?php if ($user_role != 'administrator') : ?>
        <h2>Initiative map for <?php echo $user_hub_name; ?></h2>
        <?php
        $args = array(
          'posts_per_page' => -1,
          'post_type' => 'maps',
          'post_status' => 'publish'
        );

        $posts = get_posts($args);

        if ($posts) :
          foreach ($posts as $post) :
          setup_postdata($post);

        $author_object = get_user_by('id', get_the_author_id());
        $author_hub_name = get_the_terms($author_object, 'hub')[0]->name;
        $author_hub_id = get_the_terms($author_object, 'hub')[0]->term_id;

        if ($user_hub_id == $author_hub_id) :
              //If you're a member of the hub, show the iframe post
        $iframe_url = ($post->guid); ?>
              <p>Copy and paste the HTML below:</p>
              <pre>&lt;iframe&nbsp;src&#61;&quot;<?php echo $iframe_url; ?>&quot;&nbsp;width&#61;&quot;100%&quot;&nbsp;height&#61;&quot;600px&quot;&gt;</pre>
              
              <ul class="button-group">
                <li><a class="btn btn-primary" href="<?php echo $iframe_url; ?>">View iframe map</a></li>
              </ul>
              
              <?php if ($user_hub_id == $author_hub_id) :
                $map_exists = true;
              endif; ?>
            <?php endif; ?>
              
            <?php if (($user_role == 'super_hub') && ($user_hub_id == $author_hub_id)) :
              //If you're a member of the hub and a super hub guy ?>
              <ul class="button-group">
                <li><a class="btn btn-danger" href="<?php echo get_delete_post_link(get_the_ID()); ?>">Delete Map iframe</a></li>
              </ul>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php wp_reset_postdata(); ?>

        <?php endif; ?>

        <?php if (!$map_exists) {
          echo '<p>No map has been created for your hub.</p>';
          if ($user_role != 'super_hub') {
            echo '<p>Please ask your super hub user to create one.</p>';
          }
        } ?>
        
        <?php if (($user_role === 'super_hub') && (!$map_exists)) :
          acf_form(array(
          'post_id' => 'new_post',
          'post_title' => false,
          'post_content' => false,
          'submit_value' => 'Create Map iframe',
          'updated_message' => false,
          'new_post' => array(
            'post_type' => 'maps',
            'post_status' => 'publish',
            'post_title' => $user_hub_name
          )
        ));
        endif;
        else :
          echo '<h2>No hub map is associated with admin accounts</h2>';
        echo '<p>Please log in with a non-admin account to use maps. A map must be associated with a hub.</p>';
        endif; ?>
    </section>

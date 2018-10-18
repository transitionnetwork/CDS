<?php
$user_role = wp_get_current_user()->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));
$user_hub = get_the_terms(wp_get_current_user(), 'hub');
$user_hub_name = $user_hub[0]->name;
$user_hub_id = ($user_hub[0]->term_id);

acf_form_head(); ?>

<main>
  <div class="container">
    <h1><?php echo \Tofino\Helpers\title(); ?></h1>
    <section>
      <h2>Account Details</h2>
      <div class="account-details">
      <?php
        echo '<strong>Username:</strong> ' . wp_get_current_user()->user_login . '<br />';
        echo '<strong>Email:</strong> ' . wp_get_current_user()->user_email . '<br />';
      ?>
      </div>
      <div class="button-block"><a href="#" class="btn btn-primary disabled">Edit Account</a></div>
    </section>

    <?php $args = array(
      'post_type' => 'initiatives',
      'author' => wp_get_current_user()->ID,
      'posts_per_page' => -1
    );
    $posts = get_posts($args); ?>

    <section>
      <h2>Initiatives created by me</h2>
      <?php if($posts) : ?>
        <?php include('partials/list-initiatives.php'); ?>
      <?php else : ?>
        You haven't added any initiatives yet
      <?php endif; ?>

      <div class="button-block"><a href="/add-initiative" class="btn btn-primary">Add new Initiative</a></div>
    </section>

    <?php // get all users that belong to this hub
    $hub_authors = get_objects_in_term($user_hub_id, 'hub');
    // remove logged in user
    $hub_authors = array_diff($hub_authors, array(wp_get_current_user()->ID));
    ?>

    <?php if (!current_user_can('manage_options')) : ?>
      <?php $args = array(
        'post_type' => 'initiatives',
        'posts_per_page' => -1,
        'author__in' => $hub_authors
      );
      $posts = get_posts($args); ?>

      <section>
        <h2>Initatives created by others in <?php echo $user_hub_name; ?></h2>
        <?php if ($posts) : ?>
          <?php include('partials/list-initiatives.php'); ?>
        <?php else : ?>
          You haven't added any initiatives yet
        <?php endif; ?>
      </section>
    <?php endif; ?>

    <section>
      <?php if($user_role != 'administrator') : ?>
        <h2>Initiative map for <?php echo $user_hub_name; ?></h2>
        <?php
        $args = array(
          'posts_per_page' => -1,
          'post_type' => 'maps',
          'post_status' => 'publish'
        );

        $posts = get_posts($args);

        if($posts) :
          foreach($posts as $post) :
            setup_postdata( $post );
            
            $author_object = get_user_by('id', get_the_author_id());
            $author_hub_name = get_the_terms($author_object, 'hub')[0]->name;
            $author_hub_id = get_the_terms($author_object, 'hub')[0]->term_id;

            if($user_hub_id == $author_hub_id) :
              //If you're a member of the hub, show the iframe post
              $iframe_url = ($post->guid); ?>
              <p>Copy and paste the HTML below:</p>
              <pre>&lt;iframe&nbsp;src&#61;&quot;<?php echo $iframe_url; ?>&quot;&nbsp;width&#61;&quot;100%&quot;&nbsp;height&#61;&quot;600px&quot;&gt;</pre>
              
              <ul class="button-group">
                <li><a class="btn btn-primary" href="<?php echo $iframe_url; ?>">View iframe map</a></li>
              </ul>
              
              <?php if ($user_hub_id == $author_hub_id) :
                $map_exists = TRUE;
              endif; ?>
            <?php endif; ?>
              
            <?php if(($user_role == 'super_hub') && ($user_hub_id == $author_hub_id)) :
              //If you're a member of the hub and a super hub guy ?>
              <ul class="button-group">
                <li><a class="btn btn-danger" href="<?php echo get_delete_post_link( get_the_ID() ); ?>">Delete Map iframe</a></li>
              </ul>
            <?php endif; ?>
          <?php endforeach; ?>
          <?php wp_reset_postdata(); ?>

        <?php endif; ?>

        <?php if(!$map_exists) {
          echo '<p>No map has been created for your hub.</p>';
          if($user_role != 'super_hub') {
            echo '<p>Please ask your super hub user to create one.</p>';
          }
        } ?>
        
        <?php if (($user_role === 'super_hub') && (!$map_exists)) :
          acf_form(array(
            'post_id'		=> 'new_post',
            'post_title'	=> false,
            'post_content'	=> false,
            'submit_value' => 'Create Map iframe',
            'updated_message' => false,
            'new_post'		=> array(
              'post_type'		=> 'maps',
              'post_status'	=> 'publish'
            )
          ));
        endif;
      else :
        echo '<h2>No hub map is associated with admin accounts</h2>';
        echo '<p>Please log in with a non-admin account to use maps. A map must be associated with a hub.</p>';
      endif; ?>
    </section>
  </div>
</main>

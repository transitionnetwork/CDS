<?php acf_form_head(); ?>

<main>
  <div class="container">
    <h1><?php echo \Tofino\Helpers\title(); ?></h1>
    <section>
      <h2>Account Details</h2>
      <?php
        $current_user = wp_get_current_user();
        echo '<strong>Username:</strong> ' . $current_user->user_login . '<br />';
        echo '<strong>Email:</strong> ' . $current_user->user_email . '<br />';
        echo '<strong>Display name:</strong> ' . $current_user->display_name . '<br />';
        echo '<strong>User ID:</strong> ' . $current_user->ID . '<br />';
        echo '<strong>User Role:</strong> ' . $current_user->roles[0] . '<br />';
      ?>
      <div class="button-block"><a href="#" class="btn btn-primary disabled">Edit Account</a></div>
    </section>
  
    <?php $user_id = get_current_user_id(); 

    $args = array(
      'posts_per_page' => -1,
      'author' => $user_id,
      'post_type' => 'initiatives'
    );
    
    $posts = get_posts($args); ?>
    
    <section>
      <h2>My Initiatives</h2>
      <?php if($posts && is_user_logged_in()) : ?>
        <?php foreach($posts as $post) : ?>
          <?php setup_postdata($post); ?>
          <div class="post-summary">
            <?php $params = array('edit_post' => get_the_ID()); ?>
            <a href="<?php the_permalink(); ?>" class="title"><?php the_title(); ?></a>
            <div><a class="btn btn-primary btn-sm" href="<?php the_permalink(); ?>">View</a> <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit</a></div>
          </div>
        <?php endforeach; ?>
      <?php else : ?>
        You haven't added any initiatives yet
      <?php endif; ?>

      <div class="button-block"><a href="/add-initiative" class="btn btn-primary">Add new Initiative</a></div>
    </section>

    <section>
      <h2>Map of Initiatives</h2>
      <?php
      $args = array(
        'posts_per_page' => -1,
        'author' => $user_id,
        'post_type' => 'maps'
      );
      $posts = get_posts($args);

      if($posts) :
        foreach($posts as $post) :
          $iframe_url = ($post->guid); ?>
          <p>Copy and paste the HTML below:</p>
          <pre>&lt;iframe&nbsp;src&#61;&quot;<?php echo $iframe_url; ?>&quot;&nbsp;width&#61;&quot;100%&quot;&nbsp;height&#61;&quot;600px&quot;&gt;</pre>

          <a class="btn btn-danger" href="<?php echo get_delete_post_link( get_the_ID() ); ?>">Delete Map iframe</a>

        <?php endforeach; ?>
      <?php else :
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
      endif; ?>
    </section>
  </div>
</main>

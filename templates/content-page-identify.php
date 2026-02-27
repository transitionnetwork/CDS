<?php if (!is_user_logged_in()) { ?>
  <?php
    wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
    exit;
  ?>
<?php } else { ?>
  <main>
    <div class="container">
      <?php while (have_posts()) : the_post(); ?>
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>
        
        <?php acf_form_head(); ?>
        <?php the_content(); ?>

        <?php acf_form(array(
          'post_id'		=> 'new_post',
          'post_title'	=> false,
          'post_content'	=> false,
          'submit_value' => 'Send Documents',
          'uploader' => 'basic',
          'updated_message' => __('Your documents have been sent. Thank you.', 'tofino'),
          'field_groups' => array('group_69a18f2a31e83'),
          'new_post' => array(
            'post_type' => 'post',
            'post_title' => 'dropbox-upload-' . time(),
            'post_status' => 'draft'
          )
        )); ?>
        
      <?php endwhile; ?>
    </div>
  </main>
<?php } ?>



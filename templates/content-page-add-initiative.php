<?php if (!is_user_logged_in()) {
  wp_redirect(home_url()); exit;
} else { ?>

  <?php acf_form_head(); ?>

  <?php
  // if normal user then pending status. if other then published
  $user_role = wp_get_current_user()->roles[0];
  if(($user_role == 'administrator') || ($user_role == 'super_hub')) {
    $post_status = 'publish';
  } else {
    $post_status = 'pending';
  } ?>
  <main>
    <div class="container">
      <div class="row justify-content-center">	
        <div class="col-12 col-md-10 col-lg-8">
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <?php acf_form(array(
            'post_id'		=> 'new_post',
            'post_title'	=> true,
            'post_content'	=> true,
            'return' => 'thank-you-for-your-submission?added_post',
            'submit_value' => 'Create Initiative',
            'new_post'		=> array(
              'post_type'		=> 'initiatives',
              'post_status'	=> $post_status
            ),
            'fields' => array ('logo', 'map', 'address_line_1', 'city', 'province', 'postal_code', 'country', 'email', 'website', 'twitter', 'facebook', 'instagram', 'youtube', 'additional_web_addresses', 'topic')
          ));
          echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>
        </div>
      </div>
    </div>
  </main>
  <?php acf_enqueue_uploader(); ?>

<?php } ?>

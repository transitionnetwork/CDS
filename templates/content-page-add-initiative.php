<?php if (!is_user_logged_in()) {
  wp_redirect (esc_url (add_query_arg ('error_code', '1', '/error')));
  exit;
} ?>

<?php acf_form_head(); ?>

<?php
// Ensure initiative level posts are set to pending
if(is_user_role('initiative')) {
  $post_status = 'pending';
} else {
  $post_status = 'publish';
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
          'return' => add_query_arg('added_post', 'initiative', '%post_url%'),
          'submit_value' => 'Create Initiative',
          'new_post'		=> array(
            'post_type'		=> 'initiatives',
            'post_status'	=> $post_status
          ),
          'fields' => array ('hub_tax', 'logo', 'map', 'address_line_1', 'city', 'province', 'postal_code', 'country', 'email', 'website', 'twitter', 'facebook', 'instagram', 'youtube', 'additional_web_addresses', 'topic', 'private_email')
        ));
        echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>
      </div>
    </div>
  </div>
</main>
<?php acf_enqueue_uploader(); ?>

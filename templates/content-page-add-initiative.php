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
          'field_groups' => array ('group_5a26865e56e22', 'group_5a26865e64f00', 'group_5a26865e89711', 'group_5b3e27aee4439', 'group_606d979a4877d'),
          'new_post'		=> array(
            'post_type'		=> 'initiatives',
            'post_status'	=> $post_status
          ),
        ));
        echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>
      </div>
    </div>
  </div>
</main>
<?php acf_enqueue_uploader(); ?>

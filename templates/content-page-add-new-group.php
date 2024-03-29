<?php acf_form_head(); ?>

<?php
// Ensure group level posts are set to pending
if(is_user_role('initiative')) {
  $post_status = 'pending';
  //this deals with the publish_url which already contains query arguments
  $return_url = '%post_url%' . '&added_post=initiative';
} else {
  $post_status = 'publish';
  $return_url = add_query_arg('added_post', 'initiative', '%post_url%');
} ?>

<main>
  <div class="container">
    <div class="row justify-content-center">	
      <div class="col-12 col-md-10 col-lg-8">
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>
        
        <?php if(!is_user_logged_in()) { ?>
          <?php the_content(); ?>
          <?php get_template_part('templates/partials/login-or-register'); ?>
        <?php } else { ?>
          <?php acf_form(array(
            'post_id'		=> 'new_post',
            'post_title'	=> true,
            'return' => $return_url,
            'submit_value' => 'Create Group',
            'uploader' => 'basic',
            'new_post'		=> array(
              'post_type'		=> 'initiatives',
              'post_status'	=> $post_status
            ),
          ));
          echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>
        <?php } ?>
      </div>
    </div>
  </div>
</main>

<?php acf_enqueue_uploader(); ?>

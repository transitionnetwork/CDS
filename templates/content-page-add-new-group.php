<?php acf_form_head(); ?>

<?php
// Ensure group level posts are set to pending
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
        <?php if(!is_user_logged_in()) { ?>
          <?php the_content(); ?>
          <p>
            <a class="btn btn-outline" href="<?php echo parse_post_link(460); ?>"><?php echo svg('key'); ?><?php _e('Register as an individual', 'tofino'); ?></a>
          </p>
        <?php } else { ?>
          <?php acf_form(array(
            'post_id'		=> 'new_post',
            'post_title'	=> true,
            'return' => add_query_arg('added_post', 'initiative', '%post_url%'),
            'submit_value' => 'Create Group',
            // 'field_groups' => array ('group_5a26865e56e22', 'group_5a26865e64f00', 'group_5a26865e89711', 'group_5b3e27aee4439', 'group_606d979a4877d'),
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

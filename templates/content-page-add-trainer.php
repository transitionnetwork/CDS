<?php acf_form_head(); ?>

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
            'post_content'	=> false,
            'return' => add_query_arg('added_post', 'trainer', '%post_url%'),
            'submit_value' => 'Submit Trainer',
            'field_groups' => array ('group_60ccb2664b168', 'group_5a26865e64f00', 'group_5a26865e89711'),
            'new_post'		=> array(
              'post_type'		=> 'trainers',
              'post_status'	=> 'pending'
            ),
          ));
          echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>
        <?php } ?>
      </div>
    </div>
  </div>
</main>

<?php acf_enqueue_uploader(); ?>

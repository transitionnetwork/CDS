
<?php acf_form_head(); ?>
<main>
  <div class="container">
    <div class="row justify-content-center">	
      <div class="col-12 col-md-10 col-lg-8">
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>

            <?php acf_form(array(
              'post_id'		=> 'new_post',
              'post_title'	=> true,
              'post_content'	=> true,
              'submit_value' => 'Send Request',
              'uploader' => 'basic',
              'updated_message' => __('Your request has been sent. Thank you.', 'tofino'),
              'new_post'		=> array(
                'post_type'		=> 'hub_applications',
                'post_status'	=> 'draft'
              ),
            ));
            echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

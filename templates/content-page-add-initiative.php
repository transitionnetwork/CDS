<?php 
acf_form_head();
?>

<main>
  <div class="container">	
    <h1><?php echo \Tofino\Helpers\title(); ?></h1>
    <?php
    acf_form(array(
      'post_id'		=> 'new_post',
      'post_title'	=> true,
      'post_content'	=> false,
      'return' => 'thank-you-for-your-submission',
      'submit_value' => 'Create Initiative',
      'new_post'		=> array(
        'post_type'		=> 'initiatives',
        'post_status'	=> 'publish'
      )
    ));
    echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>
  </div>
</main>

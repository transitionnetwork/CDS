<?php acf_form_head(); ?>

<main>
  <div class="container">
    <div class="mx-auto max-w-3xl w-full">
      <div>
        <h1><?php echo \Tofino\Helpers\title(); ?></h1>
        
        <div class="rich-text"><?php the_content(); ?></div>

        <?php if(!is_user_logged_in()) { ?>
          <p>To fil out the form please:</p>
          <?php get_template_part('templates/partials/login-or-register'); ?>
        <?php } else { ?>
          <?php acf_form(array(
            'post_id'		=> 'new_post',
            'post_title'	=> false,
            'post_content'	=> false,
            'return' => add_query_arg('added_post', 'trainer', '%post_url%'),
            'submit_value' => __('Add New Trainer'),
            'uploader' => 'basic',
            'new_post'		=> array(
              'post_type'		=> 'trainers',
              'post_status'	=> 'pending'
            )
          )); ?>
          <div class="button-block">
            <a class="btn btn-secondary" href="javascript:history.go(-1)"><?php _e('Cancel', 'tofino'); ?></a>
          </div>

          <?php $footer_text = get_field('footer_text') ; ?>
          <?php echo ($footer_text) ? $footer_text : null; ?>
        <?php } ?>
      </div>
    </div>
  </div>
</main>

<?php acf_enqueue_uploader(); ?>

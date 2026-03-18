<?php acf_form_head(); ?>

<main>
  <div class="container">
    <div class="mx-auto max-w-3xl w-full">
      <div>
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>

          <?php if(!is_user_logged_in()) { ?>
            <div class="rich-text"><?php the_content(); ?></div>

            <?php get_template_part('templates/partials/login-or-register'); ?>
          <?php } else { ?>
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
          <?php } ?>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

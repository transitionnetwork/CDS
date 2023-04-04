<?php $initative_id = get_query_var('initiative_id'); ?>

<?php acf_form_head(); ?>
<?php while (have_posts()) : the_post();


if (!$initiative_id || !is_user_logged_in() || !is_user_role(array('super_hub', 'administrator'))) {
  wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
  exit;
} else { ?>
  <main>
    <div class="container">
      <div class="row justify-content-center">	
        <div class="col-12 col-md-10 col-lg-8">
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          
          <?php acf_form(array(
            'post_id'		=> 'new_post',
            'post_title'	=> false,
            'return' => add_query_arg('added_note', 'initiative', get_the_permalink($initiative_id)),
            'submit_value' => 'Add note',
            'uploader' => 'basic',
            'new_post'		=> array(
              'post_type'		=> 'initiative_notes',
              'post_status'	=> 'publish'
            ),
            'html_after_fields' => '<input type="hidden" name="initiative_id" value="' . $initiative_id . '"/>'
          ));
          
          echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>

        </div>
      </div>
    </div>
  </main>

<?php } ?>

<?php endwhile; ?>

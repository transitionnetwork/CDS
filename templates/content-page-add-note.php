<?php acf_form_head(); ?>
<?php while (have_posts()) : the_post();

if (get_query_var('initiative_id') && is_user_logged_in() && (is_user_role(array('super_hub', 'administrator')) || (is_user_role('hub') && is_post_in_user_hub(get_query_var('initiative_id'))))) { ?>
  <?php $initative_id = get_query_var('initiative_id'); ?>
  <main>
    <div class="container">
      <div class="row justify-content-center">	
        <div class="col-12 col-md-10 col-lg-8">
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <h4>
            to <?php echo get_the_title(get_query_var('initiative_id')); ?>
          </h4>

          <?php acf_form(array(
            'post_id'		=> 'new_post',
            'post_title'	=> false,
            'return' => add_query_arg('added_note', 'initiative', get_the_permalink(get_query_var('initiative_id'))),
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
<?php } else {
  wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
  exit; ?>
<?php } ?>

<?php endwhile; ?>

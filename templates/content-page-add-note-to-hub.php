<?php $hub_id = (int)get_query_var('hub_id'); ?>

<?php acf_form_head(); ?>
<?php while (have_posts()) : the_post();


if (!$hub_id || !is_user_logged_in() || !is_user_role(array('super_hub', 'administrator'))) {
  wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
  exit;
} else { ?>
  <main>
    <div class="container">
      <div class="row justify-content-center">	
        <div class="col-12 col-md-10 col-lg-8">
          <h1>Add Note</h1>
          <?php $term = get_term_by('term_id', $hub_id, 'hub'); ?>
          <h4>
            to <?php echo $term->name; ?>
          </h4>
          
          <?php acf_form(array(
            'post_id'		=> 'new_post',
            'post_title'	=> false,
            'return' => add_query_arg('added_note', 'hub', get_query_var('source')),
            'submit_value' => 'Add note',
            'uploader' => 'basic',
            'new_post'		=> array(
              'post_type'		=> 'initiative_notes',
              'post_status'	=> 'publish'
            ),
            'html_after_fields' => '<input type="hidden" name="initiative_id" value="' . $hub_id . '"/>'
          ));
          
          echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>

        </div>
      </div>
    </div>
  </main>

<?php } ?>

<?php endwhile; ?>

<?php
$edit_post_id = get_query_var('edit_post'); ?>
<?php if(!can_write_initiative($post) || !isset($edit_post_id)) {
	wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
  exit;
} else { ?>
	<main>
		<div class="container">
			<div class="row justify-content-center">	
        <div class="col-12 col-md-10 col-lg-8">
					<h1><?php echo \Tofino\Helpers\title(); ?></h1>
					<?php $field_args = array('logo', 'map', 'address_line_1', 'city', 'province', 'postal_code', 'country', 'email', 'website', 'twitter', 'facebook', 'instagram', 'youtube', 'additional_web_addresses', 'topic');
					if(can_write_healthcheck($post)) {
						$field_args[] = 'private_email';
						var_dump($field_args);
					}
					acf_form_head ();
					acf_form(array(
						'post_id'		=> $edit_post_id,
						'post_title'	=> true,
						'post_content'	=> true,
						'return' => 'thank-you-for-your-submission?edited_post=' . $edit_post_id,
						'submit_value' => 'Save changes',
						'fields' => $field_args
					)); ?>
				</div>
			</div>
		</div>
	</main>
<?php } ?>

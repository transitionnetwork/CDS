<?php while (have_posts()) : the_post(); ?>
	<?php
	$edit_post_id = get_query_var('edit_post');
	$post = get_post($edit_post_id);
	setup_postdata($post);
	if(!can_write_initiative($post) || !isset($edit_post_id)) {
		wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
		exit;
	} else { ?>
		<?php wp_reset_postdata(); ?>
		<main>
			<div class="container">
				<div class="row justify-content-center">	
					<div class="col-12 col-md-10 col-lg-8">
						<h1><?php echo \Tofino\Helpers\title(); ?></h1>
						<?php $field_args = array('hub_tax', 'logo', 'map', 'address_line_1', 'city', 'province', 'postal_code', 'country', 'email', 'website', 'twitter', 'facebook', 'instagram', 'youtube', 'additional_web_addresses', 'topic', 'private_email');
						if(can_write_healthcheck($post)) {
							$field_args[] = 'private_email';
						}
						acf_form_head();
						acf_form(array(
							'post_id'		=> $edit_post_id,
							'post_title'	=> true,
							'post_content'	=> true,
							'return' => add_query_arg('edited_post', 'initiative', parse_post_link($edit_post_id)),
							'submit_value' => 'Save changes',
							'fields' => $field_args
						)); ?>
					</div>
				</div>
			</div>
		</main>
	<?php } ?>
<?php endwhile; ?>

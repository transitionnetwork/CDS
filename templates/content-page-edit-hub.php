<?php while (have_posts()) : the_post(); ?>
	<?php
	$hub_id = get_query_var('hub_id');
	$hub = get_term_by('id', get_query_var('hub_id'), 'hub');
	if(!is_user_role('administrator') && !is_user_role('super_hub') && !can_edit_hub($hub_id)) {
		wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
		exit;
	} else { ?>
		<?php wp_reset_postdata(); ?>
		<main>
			<div class="container">
				<div class="row justify-content-center">	
					<div class="col-12 col-md-10 col-lg-8">
						<h1>Edit Hub: <?php echo $hub->name; ?></h1>
						<?php 
						acf_form_head();
						$args = array(
							'post_id'		=> 'term_' . $hub_id,
							'post_title'	=> false,
							'post_content'	=> false,
							'return' => add_query_arg('updated', 'hub', parse_post_link(24)),
							'fields' => array('status', 'hub_description', 'logo', 'map', 'address_line_1', 'city', 'province', 'postal_code', 'country', 'email', 'website', 'twitter', 'facebook', 'instagram', 'youtube', 'additional_web_addresses'),
							'submit_value' => 'Save changes'
						);
						acf_form($args);
						?>
					</div>
				</div>
			</div>
		</main>
	<?php } ?>
<?php endwhile; ?>

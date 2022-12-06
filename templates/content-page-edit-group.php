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

		<?php acf_form_head(); ?>

		<main>
			<div class="container">
				<div class="row justify-content-center">	
					<div class="col-12 col-md-10 col-lg-8">
						<h1><?php echo \Tofino\Helpers\title(); ?></h1>
						<?php
						
						$field_groups = array('group_5a26865e56e22', 'group_5a26865e64f00', 'group_5a26865e89711');
						
						if(can_write_healthcheck($post)) {
							$field_groups[] = 'group_5b3e27aee4439';
						}

						$field_groups[] = 'group_606d979a4877d';

						acf_form(array(
							'post_id'		=> $edit_post_id,
							'post_title'	=> true,
							'return' => add_query_arg('edited_post', 'initiative', parse_post_link($edit_post_id)),
							'submit_value' => 'Save changes',
							'field_groups' => $field_groups
						)); ?>
					</div>
				</div>
			</div>
		</main>
	<?php } ?>
<?php endwhile; ?>

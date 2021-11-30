<?php acf_form_head(); ?>

<?php while (have_posts()) : the_post(); ?>
	<?php
	if(!is_user_trainer_admin() || !get_query_var('edit_post')) {
		wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
		exit;
	} else { ?>
		<?php wp_reset_postdata(); ?>
		
		<main>
			<div class="container">
				<div class="row justify-content-center">	
					<div class="col-12 col-md-10 col-lg-8">
						<h1>Edit Trainer: <?php echo get_the_title(get_query_var('edit_post')); ?></h1>
						
						<?php 
						acf_form(array(
							'post_id'		=> get_query_var('edit_post'),
							'post_title'	=> false,
							'post_content'	=> false,
							'return' => add_query_arg('updated', 'trainer', get_the_permalink(get_query_var('edit_post'))),
							'submit_value' => 'Update Trainer',
						));
						?>

						<div class="button-block">
            	<a class="btn btn-secondary" href="javascript:history.go(-1)"><?php _e('Cancel', 'tofino'); ?></a>
          	</div>
					</div>
				</div>
			</div>
		</main>
	
	<?php } ?>
<?php endwhile; ?>

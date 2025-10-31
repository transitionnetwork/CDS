<?php acf_form_head(); ?>

<?php while (have_posts()) : the_post(); ?>
	<?php
	$post_id = get_query_var('edit_post');
	if (!$post_id || !is_user_logged_in() || !is_user_role(array('super_hub', 'administrator'))) {
  wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
  exit;
	} else { ?>
		<?php wp_reset_postdata(); ?>
    <?php $initiative_id = get_post_meta($post_id, 'initiative_id', true); ?>
		
    <main>
			<div class="container">
				<div class="row justify-content-center">	
					<div class="col-12 col-md-10 col-lg-8">
						<h1><?php echo \Tofino\Helpers\title(); ?></h1>
						<h4>
							to <?php echo get_the_title($initiative_id); ?>
						</h4>
						<?php

						acf_form(array(
							'post_id'		=> $post_id,
							'post_title'	=> false,
							'return' => add_query_arg('edited_note', 'initiative', get_the_permalink($initiative_id)),
							'submit_value' => 'Save changes',
							'uploader' => 'basic',
						));
            
            echo '<div class="button-block"><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></div>'; ?>

					</div>
				</div>
			</div>
		</main>
	<?php } ?>
<?php endwhile; ?>

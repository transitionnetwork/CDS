<?php 
acf_form_head();
?>

<?php
$edit_post_id = get_query_var('edit_post');
$post_author = (get_post_field ('post_author', $edit_post_id));
$current_author = ((string)get_current_user_id()); ?>

<?php if($edit_post_id) : ?>
	<main>
		<div class="container">
			<div class="row justify-content-center">	
        <div class="col-12 col-md-10 col-lg-8">
					<h1><?php echo \Tofino\Helpers\title(); ?></h1>

					<?php var_dump(current_user_can( 'manage_options' )); ?>

					<?php if(($post_author == $current_author) || (current_user_can( 'manage_options' ))) : 
						acf_form(array(
							'post_id'		=> $edit_post_id,
							'post_title'	=> true,
							'post_content'	=> true,
							'return' => 'thank-you-for-your-submission?edited_post=' . $edit_post_id,
							'submit_value' => 'Save changes',
							'fields' => array('logo', 'map', 'address_line_1', 'city', 'province', 'postal_code', 'country', 'email', 'website', 'twitter', 'facebook', 'instagram', 'youtube', 'additional_web_addresses', 'topic')
						)); ?>
						<ul class="button-group">
							<li><a class="btn btn-danger" href="<?php  echo get_delete_post_link($edit_post_id); ?>" onclick="return confirm('Are you sure you want to remove this hub ?')">Delete this initiative</a></li>
							<li><a class="btn btn-secondary" href="javascript:history.go(-1)">Go Back</a></li>
						</ul>
					<?php else : ?>
						You don't have the permission to edit this post
						<div class="button-block"><a href="javascript:history.go(-1)" class="btn btn-secondary">Go Back</a></div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</main>
<?php endif; ?>

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
			<h1><?php echo \Tofino\Helpers\title(); ?></h1>
			<?php if(($post_author == $current_author) || (current_user_can( 'manage_options' ))) : 
				acf_form(array(
					'post_id'		=> $edit_post_id,
					'post_title'	=> true,
					'post_content'	=> false,
					'return' => 'thank-you-for-your-submission?edited_post='.$edit_post_id,
					'submit_value' => 'Save changes',
					'new_post'		=> array(
						'post_type'		=> 'initiatives',
						'post_status'	=> 'publish'
					)
				)); ?>
				<ul class="button-group">
					<li><a class="btn btn-danger" href="<?php  echo get_delete_post_link($edit_post_id); ?>" onclick="return confirm('Are you sure you want to remove this hub ?')">Delete this initiative</a></li>
					<li><a class="btn btn-secondary" href="javascript:history.go(-1)">Cancel</a></li>
				</ul>
			<?php else : ?>
				You don\'t have the permission to edit this post';
				<div class="button-block"><a href="javascript:history.go(-1)">Go Back</a></div>
			<?php endif; ?>
		</div>
	</main>
<?php endif; ?>

<?php 
function ma_add_co_author_tax() {
	// Add new "co-authors" taxonomy to Posts
	register_taxonomy('co_author', 'initiatives', array(
		// Hierarchical taxonomy (like categories)
		'hierarchical' => false,
		'show_admin_column' => true,
		// This array of options controls the labels displayed in the WordPress Admin UI
		'labels' => array(
			'name' => _x( 'Co-Authors', 'taxonomy general name' ),
			'singular_name' => _x( 'Co-Author', 'taxonomy singular name' ),
			'search_items' =>  __( 'Search Co-Authors' ),
			'popular_items' => __('Popular Co-Authors'),
			'all_items' => __( 'All Co-Authors' ),
			'edit_item' => __( 'Edit Co-Author' ),
			'update_item' => __( 'Update Co-Author' ),
			'separate_items_with_commas' => __( 'Separate co-authors with commas' ),
			'add_new_item' => __( 'Add New Co-Author' ),
			'add_or_remove_items' => __( 'Add or remove co-authors' ),
			'choose_from_most_used' => __( 'Choose from most used co-authors' ),			
			'new_item_name' => __( 'New Co-Author Name' ),
			'menu_name' => __( 'Co-Authors' )
		),
		// Control the slugs used for this taxonomy
		'rewrite' => array(
			'slug' => 'co-author', // This controls the base slug that will display before each term
			'with_front' => true, // Don't display the category base before "/co-authors/"
			'hierarchical' => false // This will allow URL's like "/co-authors/boston/cambridge/"
		),
	));
}
add_action( 'init', 'ma_add_co_author_tax');

function ma_post_requests() {
	if(array_key_exists('ma_remove_co_author_id', $_POST)) {
		ma_remove_co_author_from_post($_POST['ma_post_id'], $_POST['ma_remove_co_author_id']);
		wp_redirect(add_query_arg('deleted', 'co_author', get_the_permalink()));
		exit;
	}

	if(array_key_exists('ma_remove_co_waiting_author_email', $_POST)) {
		ma_option_remove_co_author_queue($_POST['ma_remove_co_waiting_author_email']);
		wp_redirect(add_query_arg('deleted', 'co_author', get_the_permalink()));
		exit;
	}

	if(array_key_exists('ma_make_primary_author', $_POST)) {
		$args = array(
			'ID' => $_POST['ma_post_id'],
			'post_author' => $_POST['ma_make_primary_author']
		);
		wp_update_post($args);
		
		ma_remove_co_author_from_post($_POST['ma_post_id'], $_POST['ma_make_primary_author']);

		wp_redirect(add_query_arg('promoted', 'co_author', get_the_permalink()));
		exit;
	}

	if(array_key_exists('ma_add_co_author_email', $_POST)) {
		$user = get_user_by( 'email', $_POST['ma_add_co_author_email'] );

		if($user) {

			$post_author_id = get_post_field( 'post_author', $_POST['ma_post_id'] );

			if($user->ID === (int)$post_author_id) {
				wp_redirect(add_query_arg('failed', 'co_author', get_the_permalink()));
				exit;			
			} else {
				//user exists - add co-author
				ma_add_co_author_to_post($_POST['ma_post_id'], $user->ID);
				//Send notification email
				ma_send_notification_email(9261, $_POST['ma_post_id'], $_POST['ma_add_co_author_email']);
				wp_redirect(add_query_arg('added', 'co_author', get_the_permalink()));
				exit;
			}

			
		} else {
			//Send invitation email
			ma_send_notification_email(9262, $_POST['ma_post_id'], $_POST['ma_add_co_author_email']);
			
			//Enqueue email in options table
			ma_option_add_co_author_queue($_POST['ma_post_id'], $_POST['ma_add_co_author_email']);

			wp_redirect(add_query_arg('added', 'co_author_invited', get_the_permalink()));
			exit;
		}
	}
}

if (!is_admin()) {
  add_action('template_redirect', 'ma_post_requests');
}

function ma_add_co_author_to_post($post_id, $user_id) {
	wp_set_object_terms($post_id, strval($user_id), 'co_author', true);
}

function ma_remove_co_author_from_post($post_id, $user_id) {
	wp_remove_object_terms( $post_id, strval($user_id), 'co_author');
}

function ma_is_co_author($post_id, $user_id = null) {

	if(!$user_id) {
		$user_id = get_current_user_id();
	}
	
	$terms = get_the_terms( $post_id, 'co_author');
	if($terms) {
		foreach($terms as $term) {
			if ((int)$term->slug == (int)$user_id) {
				return true;
			}
		}
	}

	return false;
}

function ma_get_co_authors($post_id) {
	$terms = get_the_terms($post_id, 'co_author');
	if($terms) {
		$term_list = array();
		foreach($terms as $term) {
			$term_list[] = (int)$term->slug;
		}
		return $term_list;
	}
}

function ma_user_id_exists($user_id){
	$user = get_userdata( $user_id );
	if($user) {
		return true;
	}
	return false;
}

function ma_replace_email_tags($message, $post_id) {
	// #post_name# (group name)
	$message = str_replace('#post_name#', get_the_title($post_id), $message);
	
	// #post_name_link#  (linked group with name)
	$message = str_replace('#post_name_link#', '<a href="' . get_the_permalink($post_id) . '">' . get_the_title($post_id) . '</a>', $message);
	
	// #author_email# (group author)
	$author_id = get_post_field ('post_author', $post_id);
	$message = str_replace('#author_email#', get_the_author_meta('user_email', $author_id), $message);

	return $message;
}

function ma_send_notification_email($email_id, $group_id, $to) {
	$subject = get_field('subject', $email_id);
	$body = apply_filters('the_content', get_post_field('post_content', $email_id));
	$headers = 'X-Mailgun-Variables: {"post_id" : ' . $group_id . '}';

	$subject = ma_replace_email_tags($subject, $group_id);
	$body = ma_replace_email_tags($body, $group_id);

	wp_mail($to, $subject, $body, $headers);
}

function ma_option_add_co_author_queue($post_id, $user_email) {
	add_option('waiting_co_authors');
	
	$list = get_option( 'waiting_co_authors');
	if(is_string($list)) {
		$list = array();
	}

	$list[] = array(
		'post_id' => $post_id,
		'user_email' => $user_email
	);

	//remove duplicate requests
	$list = array_unique($list, SORT_REGULAR);
	
	update_option('waiting_co_authors', $list, false);
}

function ma_option_remove_co_author_queue($email) {
	$list = get_option('waiting_co_authors');
	$list = array_values($list);
	
	$keys = array_keys(array_column($list, 'user_email'), $email);

	if($keys) {
		foreach($keys as $key) {
			unset($list[$key]);
		}
	}

	update_option('waiting_co_authors', $list, false);
}

function ma_option_get_co_author_queue($email) {
	$list = get_option('waiting_co_authors');

	$keys = array_keys(array_column($list, 'user_email'), $email);

	if($keys) {
		$post_ids = array();
		foreach($keys as $key) {
			$post_ids[] = $list[$key]['post_id'];
		}

		return($post_ids);
	}

	return;
}

//hook into user registration
add_action( 'user_register', 'ma_registration_save', 10, 1 );

function ma_registration_save( $user_id ) {
	$user_email = get_the_author_meta('user_email', $user_id);
	$post_ids = ma_option_get_co_author_queue($user_email);
	
	if($post_ids) {
		foreach($post_ids as $post_id) {
			ma_add_co_author_to_post($post_id, $user_id);
		}
	}
	
	ma_option_remove_co_author_queue($user_email);
}

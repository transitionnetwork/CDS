<?php

/**
 * Config array
 */
$svg_url       = get_template_directory_uri() . '/dist/svg/sprite.symbol.svg';
$svg_file_path = get_template_directory() . '/dist/svg/sprite.symbol.svg';

$theme_config = [
  'svg' => ['sprite_file' => $svg_url . '?v=' . filemtime($svg_file_path)]
];


/**
 * Tofino includes
 *
 * The $tofino_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed.
 *
 * Missing files will produce a fatal error.
 *
 */
$tofino_includes = [
  'src/data-tables/contact-form-data.php',
  "src/forms/contact-form.php",
  "src/lib/nav-walker.php",
  "src/lib/init.php",
  "src/lib/assets.php",
  "src/lib/helpers.php",
  "src/lib/pagination.php",
  "src/lib/list.php",
  "src/shortcodes/copyright.php",
  "src/shortcodes/social-icons.php",
  "src/shortcodes/svg.php",
  "src/shortcodes/theme-option.php",
  "src/theme-options/admin.php",
  "src/theme-options/advanced.php",
  "src/theme-options/client-data.php",
  "src/theme-options/footer.php",
  "src/theme-options/google-analytics.php",
  "src/theme-options/google-recaptcha.php",
  "src/theme-options/init.php",
  "src/theme-options/maintenance-mode.php",
  "src/theme-options/menu.php",
  "src/theme-options/notifications.php",
  "src/theme-options/social-networks.php",
  "src/theme-options/theme-tracker.php",
  "src/theme-options/dashboard-widget.php",
];

foreach ($tofino_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'tofino'), $file), E_USER_ERROR);
  }
  require_once $filepath;
}
unset($file, $filepath);


/**
 * Composer dependencies
 *
 * External dependencies are defined in the composer.json and autoloaded.
 * Use 'composer dump-autoload -o' after adding new files.
 *
 */
if (file_exists(get_template_directory() . '/vendor/autoload.php')) { // Check composer autoload file exists. Result is cached by PHP.
  require_once 'vendor/autoload.php';
} else {
  if (is_admin()) {
    add_action('admin_notices', 'composer_error_notice');
  } else {
    wp_die(composer_error_notice(), __('An error occured.', 'tofino'));
  }
}

// Check for missing dist directory. Result is cached by PHP.
if (!is_dir(get_template_directory() . '/dist')) {
  if (is_admin()) {
    add_action('admin_notices', 'missing_dist_error_notice');
  } else {
    wp_die(missing_dist_error_notice(), __('An error occured.', 'tofino'));
  }
}

// Admin notice for missing composer autoload.
function composer_error_notice() {
?><div class="error notice">
    <p><?php _e('Composer autoload file not found. Run composer install on the command line.', 'tofino'); ?></p>
  </div><?php
}

// Admin notice for missing dist directory.
function missing_dist_error_notice() {
?><div class="error notice">
    <p><?php _e('/dist directory not found. You probably want to run npm install and gulp on the command line.', 'tofino'); ?></p>
  </div><?php
}

// Custom image sizes
add_image_size( 'event', 600, 400, true );

//Custom Post Types
add_action( 'init', 'create_posttypes' );
function create_posttypes() {
  register_post_type( 'initiatives',
    array(
      'labels' => array(
        'name' => __( 'Initiatives' ),
        'singular_name' => __( 'Initiative' )
      ),
      'public' => true,
      'has_archive' => true,
      'supports' => array('title', 'editor', 'author')
    )
  );
  register_post_type( 'maps',
    array(
      'labels' => array(
        'name' => __( 'Maps' ),
        'singular_name' => __( 'Map' )
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title', 'author')
    )
  );
}


// Create user taxonomies
function create_user_taxonomies() {
  register_taxonomy('hub', 'user', array(
    'public'       => true,
    'single_value' => false,
    'show_admin_column' => true,
    'labels' => array(
      'name'                      =>'Hubs',
      'singular_name'             =>'Hub',
      'menu_name'                 =>'Hubs',
      'search_items'              =>'Search Hubs',
      'popular_items'             =>'Popular Hubs',
      'all_items'                 =>'All Hubs',
      'edit_item'                 =>'Edit Hub',
      'update_item'               =>'Update Hub',
      'add_new_item'              =>'Add New Hub',
      'new_item_name'             =>'New Hub Name',
      'separate_items_with_commas'=>'Separate hubs with commas',
      'add_or_remove_items'       =>'Add or remove hubs',
      'choose_from_most_used'     =>'Choose from the most popular hubs',
    ),
  ));
}
add_action( 'init', 'create_user_taxonomies' );

// Create custom taxonomies
function create_initiative_taxonomies() {
// Add new taxonomy, make it hierarchical (like categories)
  $labels = array(
    'name' => _x('Topics', 'taxonomy general name'),
    'singular_name' => _x('Topic', 'taxonomy singular name'),
    'search_items' => __('Search Topics'),
    'all_items' => __('All Topics'),
    'parent_item' => __('Parent Topic'),
    'parent_item_colon' => __('Parent Topic:'),
    'edit_item' => __('Edit Topic'),
    'update_item' => __('Update Topic'),
    'add_new_item' => __('Add New Topic'),
    'new_item_name' => __('New Topic Name'),
    'menu_name' => __('Topic'),
  );

  $args = array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'topic', 'with_front' => false),
  );

  register_taxonomy('topic', array('initiatives'), $args);

  $labels = array(
    'name' => _x('Countries', 'taxonomy general name'),
    'singular_name' => _x('Country', 'taxonomy singular name'),
    'search_items' => __('Search Countries'),
    'all_items' => __('All Countries'),
    'parent_item' => __('Parent Country'),
    'parent_item_colon' => __('Parent Country:'),
    'edit_item' => __('Edit Country'),
    'update_item' => __('Update Country'),
    'add_new_item' => __('Add New Country'),
    'new_item_name' => __('New Country Name'),
    'menu_name' => __('Country'),
  );

  $args = array(
    'hierarchical' => true,
    'labels' => $labels,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'country', 'with_front' => false),
  );

  register_taxonomy('country', array('initiatives'), $args);
}
add_action('init', 'create_initiative_taxonomies');

function custom_query_vars_filter($vars)
{
  $vars[] = 'edit_post';
  return $vars;
}
add_filter('query_vars', 'custom_query_vars_filter');

//Redirect after post deletion
function wpse132196_redirect_after_trashing() {
  if(!is_admin()) {
    exit;
    wp_redirect(home_url('/account'));
  }
}
add_action('trashed_post', 'wpse132196_redirect_after_trashing', 10);

//Change label of Content Editor in acf_form()
function my_acf_prepare_field($field) {
  $field['label'] = "Description";
  return $field;
}
add_filter('acf/prepare_field/name=_post_content', 'my_acf_prepare_field');

// find out whether user is superhub and of the same hub as the author (accepts author ID)
function is_super_hub_author_for_post($author) {
  $author_object = get_user_by('id', $author);
  $author_hub_name = get_the_terms($author_object, 'hub')[0]->name;
  $user_hub = get_the_terms(wp_get_current_user(), 'hub');
  $user_hub_name = $user_hub[0]->name;
  $user_role = wp_get_current_user()->roles[0];

  if (($user_role == 'super_hub') && ($user_hub_name == $author_hub_name)) {
    return TRUE;
  } else {
    return FALSE;
  }
}

//Logout link with nonce
function add_logout_link($nav, $args) {
  $logoutlink = '<li class="nav-item menu-logout"><a class="nav-link" href="' . wp_logout_url(home_url()) . '">Logout</a></li>';
  if ($args->theme_location == 'primary_navigation_loggedin') {
    return $nav . $logoutlink;
  } else {
    return $nav;
  }
}
add_filter('wp_nav_menu_items', 'add_logout_link', 10, 2);

// Publish posts
function show_publish_button($post_id) {
  global $post;
  echo '<form name="front_end_publish" method="POST" action="">
    <input type="hidden" name="pid" id="pid" value="' . $post_id . '">
    <input type="hidden" name="FE_PUBLISH" id="FE_PUBLISH" value="FE_PUBLISH">
    <input type="submit" name="submit" id="submit" value="Approve Post" class="btn btn-success btn-sm">
  </form>';
}

//function to update post status
function change_post_status($post_id, $status) {
  $current_post = get_post($post_id, 'ARRAY_A');
  $current_post['post_status'] = $status;
  wp_update_post($current_post);
}

if (isset($_POST['FE_PUBLISH']) && $_POST['FE_PUBLISH'] == 'FE_PUBLISH') {
  if (isset($_POST['pid']) && !empty($_POST['pid'])) {
    change_post_status((int)$_POST['pid'], 'publish');
  }
}

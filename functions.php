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
  "src/lib/initiatives.php",
  "src/lib/healthchecks.php",
  "src/lib/permissions.php",
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
  register_post_type( 'healthchecks',
    array(
      'labels' => array(
        'name' => __( 'Healthchecks' ),
        'singular_name' => __( 'Healthcheck' )
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

// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// disable for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);

function custom_query_vars_filter($vars)
{
  $vars[] = 'edit_post';
  $vars[] = 'initiative_id';
  $vars[] = 'error_code';
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


//Healthcheck
function get_latest_healthcheck($id) {
  $args = array(
    'post_type' => 'healthchecks',
    'title' => $id,
    'posts_per_page' => 1,
    'orderby' => 'post_date',
    'order' => 'DESC'
  );
  $posts = get_posts($args);
  if($posts) {
    return date('l jS F Y - H:i', strtotime($posts[0]->post_date)); 
  } else {
    return 'Never';
  }
}

function get_user_hub_id() {
  return get_the_terms(wp_get_current_user(), 'hub')[0]->term_id;
}

function get_hub_users($hub_id) {
  $users = get_objects_in_term($hub_id, 'hub');
  return $users;
}

function get_hub_filter() {
  // Get all hubs that have users
  $terms = get_terms(array(
    'taxonomy' => 'hub',
    'hide_empty' => true,
  )); ?>

  <form action="" method="GET" id="hub-filter">
    <?php _e('Filter by hub:'); ?>
    <select name="term" id="term">
      <option value="">All</option>
      <?php foreach($terms as $term) { ?>
        <?php $users = get_hub_users($term->term_id);
        $args = array (
          'post_type' => 'initiatives',
          'author__in' => $users
        );
        $posts = get_posts($args);
        $count = count($posts);?>
        <?php if(get_query_var('term') == $term->term_id) {
          $selected = 'selected';
        } else {
          $selected = '';
        } ?>
        <?php if($count > 0) { ?>
          <option value="<?php echo $term->term_id; ?>" <?php echo $selected; ?>><?php echo $term->name; ?> (<?php echo $count; ?>)</option>
        <?php } ?>
      <?php } ?>
    </select>
    <input type="submit" value="Go">
  </form>
<?php }

if (isset($_POST['FE_PUBLISH']) && $_POST['FE_PUBLISH'] == 'FE_PUBLISH') {
  if (isset($_POST['pid']) && !empty($_POST['pid'])) {
    change_post_status((int)$_POST['pid'], 'publish');
  }
}

function acf_custom_save($post_id) {
  if (get_post_type($post_id) == 'healthchecks') {
    $my_post = array();
    $my_post['ID'] = $post_id;
    
    $post = get_post($post_id);

    //check for new post 
    if ($post->post_modified_gmt == $post->post_date_gmt) {
      $my_post['post_title'] = get_query_var('initiative_id');
    } else {
      $my_post['post_title'] = get_the_title($post_id);
    }

    wp_update_post($my_post);
  }
}
add_filter('acf/save_post', 'acf_custom_save', 20);

function archive_search($query) {
  if (!is_admin() && $query->is_main_query()) {
    $query->set('orderby', 'post_title');
    $query->set('order', 'ASC');
    
    if (is_user_role('administrator') || is_user_role('super_hub')) {
      $query->set('post_status', array('pending', 'publish'));
    }
    
    if(get_query_var('term')) {
      $users = get_hub_users(get_query_var('term'));
      $query->set('author__in', $users);
    }
  }
}
add_action('pre_get_posts', 'archive_search');

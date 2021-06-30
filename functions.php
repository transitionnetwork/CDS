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
  // 'src/data-tables/contact-form-data.php',
  // "src/forms/contact-form.php",
  "src/lib/nav-walker.php",
  "src/lib/init.php",
  "src/lib/assets.php",
  "src/lib/helpers.php",
  "src/lib/pagination.php",
  "src/lib/initiatives.php",
  "src/lib/hubs.php",
  "src/lib/healthchecks.php",
  "src/lib/permissions.php",
  "src/lib/hub-filter.php",
  "src/lib/emails.php",
  "src/lib/cron.php",
  "src/lib/custom-api-endpoints.php",
  "src/lib/output-csv.php",
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
  "src/ajax/map-requests.php",
  "src/ajax/graph-requests.php",
  "src/ajax/file-requests.php",
  "src/custom/admin-tables.php",
  "src/custom/acf-save.php",
  "src/custom/helpers.php",
  "src/custom/rank-math.php",
  "src/custom/register-types-tax.php"
  // "src/custom/retention-emailing.php",
];

foreach ($tofino_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'tofino-nt'), $file), E_USER_ERROR);
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
    wp_die(composer_error_notice(), __('An error occured.', 'tofino-nt'));
  }
}

// Check for missing dist directory. Result is cached by PHP.
if (!is_dir(get_template_directory() . '/dist')) {
  if (is_admin()) {
    add_action('admin_notices', 'missing_dist_error_notice');
  } else {
    wp_die(missing_dist_error_notice(), __('An error occured.', 'tofino-nt'));
  }
}

// Admin notice for missing composer autoload.
function composer_error_notice()
{
  ?>
<div class="error notice">
    <p>
        <?php _e('Composer autoload file not found. Run composer install on the command line.', 'tofino-nt'); ?>
    </p>
</div>
<?php

}

// Admin notice for missing dist directory.
function missing_dist_error_notice()
{
  ?>
<div class="error notice">
    <p>
        <?php _e('/dist directory not found. You probably want to run npm install and gulp on the command line.', 'tofino-nt'); ?>
    </p>
</div>
<?php

}

function dd($string)
{
  die(var_dump($string));
}

// Custom image sizes
add_image_size('event', 600, 400, true);

// disable for posts
add_filter('use_block_editor_for_post', '__return_false', 10);

// disable for post types
add_filter('use_block_editor_for_post_type', '__return_false', 10);

function custom_query_vars_filter($vars)
{
  $vars[] = 'edit_post';
  $vars[] = 'initiative_id';
  $vars[] = 'error_code';
  $vars[] = 'updated';
  $vars[] = 'deleted';
  $vars[] = 'failed';
  $vars[] = 'hub_id';
  $vars[] = 'edited_post';
  $vars[] = 'added_post';
  $vars[] = 'hub_name';
  $vars[] = 'search';
  $vars[] = 'country';
  return $vars;
}
add_filter('query_vars', 'custom_query_vars_filter');

//Redirect after post deletion
function wpse132196_redirect_after_trashing($post_id) {
  if(get_post_type($post_id) === 'files') {
    wp_redirect(add_query_arg('tab', 'file', parse_post_link(24)));
    exit;
  }
}

add_action('trashed_post', 'wpse132196_redirect_after_trashing', 10);

//Change label of Content Editor in acf_form()
function prepare_post_title($field)
{
  global $post;
  if($post->post_name === 'add-trainer') {
    $field['label'] = "Full Name";
  }
  return $field;
}
add_filter('acf/prepare_field/name=_post_title', 'prepare_post_title');

//Change label of Content Editor in acf_form()
function prepare_post_content($field)
{
  $field['label'] = "Description";
  return $field;
}
add_filter('acf/prepare_field/name=_post_content', 'prepare_post_content');

//Logout link with nonce
function add_logout_link($nav, $args)
{
  $logoutlink = '<li class="nav-item menu-logout"><a class="nav-link" href="' . wp_logout_url(home_url()) . '">Logout</a></li>';
  if ($args->theme_location == 'primary_navigation_loggedin') {
    return $nav . $logoutlink;
  } else {
    return $nav;
  }
}
// disabled for now to keep nav tidy
// add_filter('wp_nav_menu_items', 'add_logout_link', 10, 2);

//Healthcheck
function get_latest_healthcheck($post = 0)
{
  $post = get_post($post);
  
  $args = array(
    'post_type' => 'healthchecks',
    'title' => $post->ID,
    'posts_per_page' => 1,
    'orderby' => 'post_date',
    'order' => 'DESC'
  );

  $posts = get_posts($args);

  if ($posts) {
    return '<a href="' . get_permalink($posts[0]->ID) . '">' . date('j-M-Y H:i', strtotime($posts[0]->post_date)) . '</a>';
  } else {
    return 'Never';
  }
}

function get_user_hub_id($user_id)
{
  return get_term_by('slug', get_user_hub_slug($user_id), 'hub')->term_id;
}

function get_user_hub_slug($user_id) {
  return get_usermeta($user_id, 'hub', true);
}

function get_hub_users($hub_slug)
{
  $args = array(
    'meta_query' => array(
      array(
        'key' => 'hub',
        'value' => $hub_slug
      )
    )
  );
  $user_query = new WP_User_Query( $args );
  return $user_query->results;
}

function get_hub_by_id($id)
{
  return get_term($id, 'hub')->name;
}


// Hook Gravity Forms user registration -> Map taxomomy

function map_taxonomy($user_id, $config, $entry, $user_pass)
{

  global $wpdb;

  // Get all taxonomies
  $taxs = get_taxonomies();

  // Get all user meta
  $all_meta_for_user = get_user_meta($user_id);

  // Loop through meta data and map to taxonomies with same name as user meta key
  foreach ($all_meta_for_user as $taxonomy => $value) {

    if (in_array($taxonomy, $taxs)) {      // Check if there is a Taxonomy with the same name as the Custom user meta key

      // Get term id
      $term_id = get_user_meta($user_id, $taxonomy, true);
      if (is_numeric($term_id)) {        // Check if Custom user meta is an ID

        $taxonomy . '=' . $term_id . '<br>';

        // Add user to taxomomy term
        $term = get_term($term_id, $taxonomy);
        $termslug = $term->slug;
        wp_set_object_terms($user_id, array($termslug), $taxonomy, false);
      }
    }
  }
}
add_action("gform_user_registered", "map_taxonomy", 10, 4);

function generate_map($post_id)
{
  $map = get_field('map', $post_id, false);
  $map = maybe_unserialize($map);
  if ($map['center_lat']) {
    return '<li class="point" data-lat="' . htmlspecialchars($map['center_lat']) . '" data-lng="' . htmlspecialchars($map['center_lng']) . '" data-title="' . get_the_title($post_id) . '" data-link="' . get_the_permalink($post_id) . '" data-excerpt="' . get_the_excerpt($post_id) . '"></li>';
  }
  return false;
}

function wpse23007_redirect()
{
  if (is_admin() && !defined('DOING_AJAX') && (current_user_can('initiative') || current_user_can('hub'))) {
    wp_redirect(home_url());
    exit;
  }
}
add_action('init', 'wpse23007_redirect');

if (function_exists('acf_add_options_page')) {
  acf_add_options_page();
}

// set default hub value to no-hub when adding initiative
function set_tax_default($field) {
  global $post;
  if($post->post_name == 'add-initiative') {
    $field['value'] = 285;
  }
  return $field;
}
add_filter('acf/load_field/key=field_5c473dfca1fd3', 'set_tax_default');

function redirects() {
  if ('POST' == $_SERVER['REQUEST_METHOD']) {
    if(array_key_exists('accepted', $_POST)) {
      if($_POST['accepted'] == 'true') {
        update_user_meta(get_current_user_id(), '_gdpr_accepted', 'field_5c51aba1d7642');
        update_user_meta(get_current_user_id(), 'gdpr_accepted', true);
        wp_safe_redirect('account');
      }
    }

    if(array_key_exists('authors', $_POST)) {
      $args = array(
        'ID' => $_POST['post_id'],
        'post_author' => $_POST['authors']
      );
      wp_update_post($args);
      wp_safe_redirect(add_query_arg('updated', 'author', parse_post_link($_POST['post_id'])));
    }

    if(array_key_exists('unpublish', $_POST)) {
      $args = array(
        'ID' => $_POST['unpublish'],
        'post_status' => 'draft'
      );
      wp_update_post($args);
    }
  }
}

if (is_user_logged_in()) {
  add_action('template_redirect', 'redirects');
}

function get_environment() {
  if(strpos(get_site_url(), 'transitioninitiative.org') !== false) {
    return 'production';
  }
  
  if(strpos(get_site_url(), 'stage') !== false) {
    return 'stage';
  }

  return 'dev';
}

// Polylang helpers
function preserve_query_args( $url, $slug ) {
  $permitted_vars = array(
    'initiative_id',
    'post_id',
    'step',
    'token',
    'hub_name',
    'country',
    'search',
    'error_code'
  );

  // die(var_dump($permitted_vars));

  $args = array();
  foreach($permitted_vars as $var) {
    if(array_key_exists($var, $_GET) && $_GET[$var]) {
      $args[$var] = $_GET[$var];
    }
  }

  $url = add_query_arg($args, $url);

  return $url === null ? home_url( '?lang=' . $slug ) : $url;
}

add_filter( 'pll_the_language_link', 'preserve_query_args', 10, 2 );

function parse_post_link($post_id) {
  if(function_exists('pll_get_post')) {
    if(pll_get_post($post_id)) {
      return get_the_permalink(pll_get_post($post_id));
    };
  }
  return get_the_permalink($post_id);
}

function get_words($sentence, $count = 10) {
  preg_match("/(?:[^\s,\.;\?\!]+(?:[\s,\.;\?\!]+|$)){0,$count}/", $sentence, $matches);
  return $matches[0];
}

add_action('init', 'customRSS');
function customRSS() {
  add_feed('last-updated-initiatives', 'get_feed_updated_initiatives');
}

function get_feed_updated_initiatives() {
  get_template_part('templates/rss/updated-initiatives');
}

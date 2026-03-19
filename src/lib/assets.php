<?php
/**
 * Load CSS and JS files
 *
 * @package Tofino
 * @since 1.0.0
 */

namespace Tofino\Assets;

/**
 * Load styles
 *
 * Enqueue the Vite-compiled Tailwind/DaisyUI CSS.
 *
 * @since 2.0.0
 * @return void
 */
function styles() {
  $app_css = '/dist/css/app.css';
  if (file_exists(get_template_directory() . $app_css)) {
    wp_enqueue_style('tofino/css', get_template_directory_uri() . $app_css, [], filemtime(get_template_directory() . $app_css));
  }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\styles');


/**
 * Load admin styles
 *
 * Register and enqueue the stylesheet used in the admin area.
 *
 * @since 1.0.0
 * @return void
 */
function admin_styles() {
  $admin_css = '/dist/css/wp-admin.css';
  if (file_exists(get_template_directory() . $admin_css)) {
    wp_register_style('tofino/css/admin', get_template_directory_uri() . $admin_css . '?v=' . filemtime(get_template_directory() . $admin_css));
    wp_enqueue_style('tofino/css/admin');
  }
}
add_action('login_head', __NAMESPACE__ . '\\admin_styles');
add_action('admin_head', __NAMESPACE__ . '\\admin_styles');


/**
 * Main JS script
 *
 * Register and enqueue the main JS.
 *
 * @since 1.1.0
 * @return void
 */
function main_script() {
  if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
    $main_js = '/dist/js/main.js';
    wp_register_script('tofino/js', get_template_directory_uri() . $main_js . '?v=' . filemtime(get_template_directory() . $main_js), [], null, true);
    wp_enqueue_script('tofino/js');
  }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\main_script');


/**
 * Localize script
 *
 * Make data available to JS scripts via global JS variables.
 *
 * @link https://codex.wordpress.org/Function_Reference/wp_localize_script
 * @since 1.1.0
 * @return void
 */
function localize_scripts() {
  if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {
    global $post;

    $args = array(
      'ajaxUrl'        => admin_url('admin-ajax.php'),
      'nextNonce'      => wp_create_nonce('next_nonce'),
      'cookieExpires'  => (get_theme_mod('notification_expires') ? get_theme_mod('notification_expires'): 999),
      'themeUrl'       => get_template_directory_uri(),
      'notificationJS' => (get_theme_mod('notification_use_js') ? 'true' : 'false'),
      'homeUrl'        => home_url(),
      'postId'         => get_the_ID(),
      'postTitle'      => get_the_title(),
      'isFrontPage'    => is_front_page()
    );

    if($post) {
      $args['postName'] = $post->post_name;
    }

    if(is_archive()) {
      $args['postName'] = get_queried_object()->name;
    }

    wp_localize_script('tofino/js', 'tofinoJS', $args);
  }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\localize_scripts');


/**
 * Load admin scripts
 *
 * Register and enqueue the scripts used in the admin area.
 *
 * @since 1.0.0
 * @return void
 */
function admin_scripts() {
  $admin_js = '/dist/js/wp-admin.js';
  if (file_exists(get_template_directory() . $admin_js)) {
    wp_register_script('tofino/js/admin', get_template_directory_uri() . $admin_js . '?=' . filemtime(get_template_directory() . $admin_js));
    wp_enqueue_script('tofino/js/admin');
  }
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\admin_scripts');


/**
 * Add type="module" to Vite-built scripts
 *
 * Vite outputs ES modules with import/export syntax.
 * WordPress enqueues scripts as classic by default.
 *
 * @since 2.0.0
 */
function add_module_type($tag, $handle, $src) {
  $module_handles = ['tofino/js', 'tofino/js/admin'];
  if (in_array($handle, $module_handles)) {
    // Remove any existing type attribute, then add type="module"
    $tag = preg_replace('/\s*type=["\'][^"\']*["\']/', '', $tag);
    $tag = str_replace('<script ', '<script type="module" ', $tag);
  }
  return $tag;
}
add_filter('script_loader_tag', __NAMESPACE__ . '\\add_module_type', 10, 3);

<?php
use \Tofino\ThemeOptions\Menu as m;
use \Tofino\ThemeOptions\Notifications as n; ?>
<!doctype html>
<html <?php language_attributes(); ?> data-theme="cds">
<head>
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon.svg" />
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="MyWebSite" />
  <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/favicon/site.webmanifest" />

  
  <script data-goatcounter="https://transitiongroups.goatcounter.com/count"
        async src="//gc.zgo.at/count.js"></script>

  <script src="https://analytics.ahrefs.com/analytics.js" data-key="b+sXmeHi26Fyp3pKXV0AFQ" async></script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>

  <?php get_template_part('templates/header/google-ads-conversion'); ?>
</head>

<?php if ( function_exists('icl_object_id') && ICL_LANGUAGE_CODE != 'en') {
  $en_post_id = apply_filters( 'wpml_object_id', $post->ID, 'post', '', 'en' );
  $body_class = get_post($en_post_id)->post_name;
} else {
  $body_class = null;
} ?>

<?php if(is_user_logged_in(  )) {
  $user_role = wp_get_current_user()->roles[0];
  $body_class .= 'user-role-' . $user_role;
} ?>

<body <?php body_class($body_class); ?> data-pid="<?php echo ($post) ? $post->ID : null; ?>">
<div id="template-url" url="<?php echo get_template_directory_uri(); ?>"></div>
<?php n\notification('top'); ?>

<!--[if lte IE 9]>
<div class="alert alert-danger browser-warning">
<p><?php _e('You are using an <strong>outdated</strong> browser. <a href="http://browsehappy.com/">Update your browser</a> to improve your experience.', 'tofino'); ?></p>
</div>
<![endif]-->

<div class="bar-info">
  <div class="container">
    <?php get_template_part('templates/top-nav'); ?>
  </div>
</div>

<div class="drawer drawer-end">
  <input id="mobile-nav-drawer" type="checkbox" class="drawer-toggle" />

  <div class="drawer-content">
    <!-- Navbar -->
    <nav class="navbar bg-base-200 <?php echo m\menu_headroom(); ?> <?php echo m\menu_sticky(); ?> <?php echo m\menu_position(); ?>">
      <div class="container">
        <div class="navbar-start">
          <a href="<?php echo home_url(); ?>">
            <img src="<?php echo get_template_directory_uri() . '/dist/img/tn-logo.png'; ?>" class="w-44 h-auto" alt="Transition Network International Logo" title="Transition Network International Logo">
          </a>
        </div>

        <div class="navbar-end">
          <!-- Desktop menu -->
          <div class="hidden lg:block" id="main-menu">
            <?php
            if (has_nav_menu('primary_nav')) :
              wp_nav_menu([
                'menu'            => 'nav_menu',
                'theme_location'  => 'primary_nav',
                'depth'           => 2,
                'container'       => '',
                'container_class' => '',
                'container_id'    => '',
                'menu_class'      => 'navbar-nav',
                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'walker'          => new Tofino\Nav\NavWalker()
              ]);
            endif; ?>
          </div>

          <!-- Mobile hamburger -->
          <label for="mobile-nav-drawer" aria-label="<?php _e('Toggle navigation', 'tofino'); ?>" class="btn btn-ghost btn-circle lg:hidden swap swap-rotate size-12">
            <input type="checkbox" class="mobile-nav-sync" />
            <?php echo svg(['sprite' => 'menu', 'class' => 'swap-off size-8']); ?>
            <?php echo svg(['sprite' => 'x', 'class' => 'swap-on size-8']); ?>
          </label>
        </div>
      </div>
    </nav>

    <div class="wrapper">

<?php
use \Tofino\ThemeOptions\Menu as m;
use \Tofino\ThemeOptions\Notifications as n; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon.svg" />
  <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon.ico" />
  <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/favicon/apple-touch-icon.png" />
  <meta name="apple-mobile-web-app-title" content="MyWebSite" />
  <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/favicon/site.webmanifest" />

  
  <script data-goatcounter="https://transitiongroups.goatcounter.com/count"
        async src="//gc.zgo.at/count.js"></script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
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

<nav class="navbar navbar-expand-lg <?php echo m\menu_headroom(); ?> <?php echo m\menu_sticky(); ?> <?php echo m\menu_position(); ?>">
  <div class="container">
    <div class="flex-shrink-0 flex items-center mr-6">
      <a href="<?php echo home_url(); ?>">
        <img src="<?php echo get_template_directory_uri() . '/dist/img/tn-logo.png'; ?>" class="navbar-brand" alt="Transition Network International Logo" title="Transition Network International Logo">
      </a>
    </div>

    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#main-menu" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="bar-wrapper">
        <span class="bar"></span>
        <span class="bar"></span>
        <span class="bar"></span>
      </span>
      <span class="sr-only"><?php _e('Toggle Navigation Button', 'tofino'); ?></span>
    </button>
    <div class="collapse navbar-collapse" id="main-menu">
      <?php if(is_user_logged_in()) : ?>
        <?php
        if (has_nav_menu('primary_navigation_loggedin')) :
          wp_nav_menu([
            'menu'            => 'nav_menu',
            'theme_location'  => 'primary_navigation_loggedin',
            'depth'           => 2,
            'container'       => '',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'navbar-nav',
            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'walker'          => new Tofino\Nav\NavWalker()
          ]);
        endif; ?>
      <?php else : ?>
        <?php
        if (has_nav_menu('primary_navigation_loggedout')) :
          wp_nav_menu([
            'menu'            => 'nav_menu',
            'theme_location'  => 'primary_navigation_loggedout',
            'depth'           => 2,
            'container'       => '',
            'container_class' => '',
            'container_id'    => '',
            'menu_class'      => 'navbar-nav',
            'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            'walker'          => new Tofino\Nav\NavWalker()
          ]);
        endif; ?>
      <?php endif; ?>
    </div>
  </div>
</nav>

<?php if (get_theme_mod('footer_sticky') === 'enabled') : ?>
  <div class="wrapper">
<?php endif; ?>

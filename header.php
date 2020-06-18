<?php
use \Tofino\ThemeOptions\Menu as m;
use \Tofino\ThemeOptions\Notifications as n; ?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_template_directory_uri(); ?>/favicon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="<?php echo get_template_directory_uri(); ?>/favicon/favicon-16x16.png">
  <link rel="manifest" href="<?php echo get_template_directory_uri(); ?>/favicon/site.webmanifest">
  <link rel="mask-icon" href="<?php echo get_template_directory_uri(); ?>/favicon/safari-pinned-tab.svg" color="#5bbad5">
  <meta name="msapplication-TileColor" content="#da532c">
  <meta name="theme-color" content="#ffffff">


  <!-- Global site tag (gtag.js) - Google Analytics -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-75130451-4"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-75130451-4');
  </script>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<?php if ( function_exists('icl_object_id') && ICL_LANGUAGE_CODE != 'en') {
  $en_post_id = apply_filters( 'wpml_object_id', $post->ID, 'post', '', 'en' );
  $en_class = get_post($en_post_id);
} ?>

<body <?php body_class($en_class->post_name); ?> data-pid="<?php echo $post->ID; ?>">
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
    <a class="navbar-brand" href="<?php echo home_url(); ?>" title="<?php echo esc_attr(bloginfo('name')); ?>"><?php echo svg('tn-logo'); ?></a>

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

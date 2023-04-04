<?php while (have_posts()) : the_post(); ?>
<?php 
$initative_id = get_query_var('initiative_id');
$post = get_post($initiative_id);
setup_postdata($post);

//TODO ADD PERMS ON INCOMPLETE METADATA
if (!is_user_logged_in() || (!can_view_healthcheck($post)) || !get_query_var('initiative_id')) {
  wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
  exit;
} else { ?>
  <?php wp_reset_postdata(); ?>
    <main class="healthcheck-form">
      <div class="container">
        <div class="row justify-content-center">	
          <div class="col-12 col-xl-10">
            <h1><?php echo \Tofino\Helpers\title(); ?></h1>
            <?php the_content(); ?>
            <div class="header">
              <h2><a href="<?php echo parse_post_link($initiative_id); ?>"><?php echo get_the_title($initiative_id); ?></a></h2>
              <div class="date"><?php echo date('l jS F Y'); ?></div>
            </div>
            <?php acf_form_head(); ?>
            <?php echo do_shortcode('[acf_multiforms_healthcheck]'); ?>
          </div>
        </div>
      </div>
    </main>
  <?php } ?>
<?php endwhile; ?>

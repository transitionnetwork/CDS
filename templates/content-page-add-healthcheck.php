<?php if(get_query_var('edited_post') && !get_query_var('step')) { ?>
  <div class="container">
    <div class="col-12 col-xl-10">
      <div class="alert top alert-info">
        <?php $hub = get_the_terms(get_query_var('initiative_id'), 'hub')[0]->term_id; ?>
        <div class="mt-2">
          <?php if(in_array($hub, get_tt_hub_ids())) {
            echo get_field('hub_edit_tt', 'options');
          } else {
            echo get_field('hub_edit_row', 'options');
          } ?>
        </div>
      </div>
    </div>
  </div>
<?php } ?>

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
              <h2><a href="<?php echo get_the_permalink($initiative_id); ?>"><?php echo get_the_title($initiative_id); ?></a></h2>
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

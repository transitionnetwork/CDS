<?php
$initative_id = get_query_var('initiative_id');

if (!is_user_logged_in()) {
  wp_redirect(home_url()); exit;
} else { ?>
  <?php
  // if normal user then pending status. if other then published
  if(current_user_can('administrator') || current_user_can('super_hub')) {
    $post_status = 'publish';
  } else {
    $post_status = 'pending';
  } ?>
  <?php while (have_posts()) : the_post(); ?>
    <main>
      <div class="container">
        <div class="row justify-content-center">	
          <div class="col-12 col-md-10 col-lg-8">
            <h1><?php echo \Tofino\Helpers\title(); ?></h1>
            <?php the_content(); ?>
            <h2><a href="<?php echo get_the_permalink($initiative_id); ?>"><?php echo get_the_title($initiative_id); ?></a></h2>
            <h4><?php echo date('l jS F Y'); ?></h4>
            <?php acf_form_head(); ?>
            <?php acf_form(array(
              'post_id'		=> 'new_post',
              'post_title'	=> false,
              'post_content'	=> false,
              'return' => get_permalink($initiative_id),
              'submit_value' => 'Create Healthcheck',
              'new_post'		=> array(
                'post_type'		=> 'healthchecks',
                'post_status'	=> $post_status
              )
            )); ?>
          </div>
        </div>
      </div>
    </main>
  <?php endwhile; ?>
<?php } ?>
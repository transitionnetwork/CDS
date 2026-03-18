<?php if(!is_user_logged_in() || (!is_user_role('super_hub'))) {
  wp_redirect(esc_url(add_query_arg('error_code', '1', '/error')));
  exit;
} ?>

<main>
  <div class="container">
    <?php while (have_posts()) : the_post(); ?>
      <h1><?php echo \Tofino\Helpers\title(); ?> - <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?></h1>
      <?php $user_hub_id = get_the_terms(wp_get_current_user(), 'hub')[0]->term_id;
      $hub_user_ids = get_objects_in_term($user_hub_id, 'hub');
      $other_hub_user_ids = array_diff($hub_user_ids, array(wp_get_current_user()->ID));
      if($other_hub_user_ids) { ?>
        <div class="flex flex-col gap-4">
          <?php foreach($other_hub_user_ids as $user_id) :
            $user = get_user_by('ID', $user_id); ?>
            <?php if ($user) { ?>
              <div class="card card-border bg-white p-4 shadow-sm">
                <div class="flex flex-wrap gap-x-6 gap-y-1 items-center">
                  <span class="font-bold"><?php echo $user->data->display_name; ?></span>
                  <span class="text-sm text-gray-600"><?php echo ucwords(str_replace('_', ' ', $user->roles[0])); ?></span>
                  <a href="mailto:<?php echo $user->data->user_email; ?>" class="text-sm"><?php echo $user->data->user_email; ?></a>
                </div>
              </div>
            <?php } ?>
          <?php endforeach; ?>
        </div>
      <?php } else { ?>
        <p><?php _e('There are no other users registered to this hub', 'tofino'); ?></p>
      <?php } ?>
      <ul class="button-group">
        <li><a href="<?php echo get_the_permalink(24); ?>" class="btn btn-primary">&laquo <?php _e('Back to dashboard', 'tofino'); ?></a></li>
      </ul>
    <?php endwhile; ?>
  </div>
</main>

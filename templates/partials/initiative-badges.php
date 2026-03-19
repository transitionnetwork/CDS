<?php
$country_term = (get_the_terms($post, 'country')) ? get_the_terms($post, 'country')[0] : null;
$hub_terms = get_the_terms($post, 'hub');
$hub_term = ($hub_terms && count($hub_terms) >= 1) ? $hub_terms[0] : null;
?>

<div class="flex flex-col gap-1.5 text-sm">
  <?php // Row 1: Country, Hub, Vive, Unpublished ?>
  <div class="flex flex-wrap items-center gap-2">
    <?php if($country_term) { ?>
      <span class="badge badge-outline badge-primary"><?php _e('Country', 'tofino'); ?>: <?php echo $country_term->name; ?></span>
    <?php } ?>
    <?php if(!is_tax() && $hub_term) { ?>
      <a href="<?php echo get_term_link($hub_term); ?>" class="badge badge-outline badge-primary no-underline"><?php _e('Hub', 'tofino'); ?>: <?php echo $hub_term->name; ?></a>
    <?php } elseif(!is_tax()) { ?>
      <span class="badge badge-outline badge-primary"><?php _e('Hub', 'tofino'); ?>: —</span>
    <?php } ?>
    <?php if(get_post_meta($post->ID, 'vive', true)) { ?>
      <a class="badge badge-info no-underline" href="https://vive.transitiontogether.org.uk/s/transition-together/" target="_blank">Vive</a>
    <?php } ?>
    <?php if(!is_post_published($post)) { ?>
      <span class="badge badge-warning gap-1 whitespace-nowrap"><?php echo svg(['sprite' => 'alert', 'class' => 'size-4 shrink-0']); ?> <?php _e('Group is unpublished', 'tofino'); ?></span>
    <?php } ?>
  </div>

  <?php // Row 2: Created, Updated, Healthcheck ?>
  <div class="flex flex-wrap items-center gap-2">
    <?php if(is_user_role(array('administrator', 'super_hub', 'hub'))) { ?>
      <span class="badge badge-outline badge-neutral"><?php _e('Created', 'tofino'); ?>: <?php echo get_the_date('j-M-Y'); ?></span>
    <?php } ?>
    <?php if(is_user_logged_in() && can_view_healthcheck($post)) { ?>
      <span class="badge badge-outline badge-neutral"><?php _e('Updated', 'tofino'); ?>: <?php echo get_initiatve_age($post)['days'] . ' days ago'; ?></span>
    <?php } ?>
    <?php if(is_user_logged_in() && can_view_healthcheck($post)) { ?>
      <span class="badge badge-outline badge-secondary"><?php _e('Last Healthcheck', 'tofino'); ?>: <?php echo get_latest_healthcheck($post); ?></span>
    <?php } ?>
  </div>

  <?php // Row 3: Last Email + Email Event (admin/hub only) ?>
  <?php if(is_user_role(array('administrator', 'super_hub', 'hub')) && $hub_term && can_edit_hub($hub_term->term_id)) { ?>
    <div class="flex flex-wrap items-center gap-2">
      <span class="badge badge-outline"><?php _e('Last Email', 'tofino'); ?>: <?php echo get_post_meta($post->ID, 'last_mail_date', true) ?: '—'; ?></span>
      <?php
        $mail_event = get_post_meta($post->ID, 'last_mail_event', true);
        $mail_event_display = $mail_event ? ucwords($mail_event) : '—';
        $mail_event_color = (strtolower($mail_event) === 'delivered') ? 'badge-success' : 'badge-error';
        if(!$mail_event) { $mail_event_color = 'badge-outline'; }
      ?>
      <span class="badge badge-outline <?php echo $mail_event_color; ?>"><?php _e('Email Event', 'tofino'); ?>: <?php echo $mail_event_display; ?></span>
    </div>
  <?php } ?>
</div>

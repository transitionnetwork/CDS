<?php $term = 'term_' . $hub->term_id; ?>

<div class="hub-col">
  <?php if(get_field('logo', $term)) {
    $url = get_field('logo', $term)['sizes']['large'];
  } else {
    $url = get_field('placeholder', 'options')['sizes']['large']; //default
  } ?>

  <div class="hub-item">
    <a class="thumbnail-bg" href="<?php echo get_term_link($hub); ?>" style="background-image: url(<?php echo $url; ?>)"></a>
    <div class="content">
      <h3><a href="<?php echo get_term_link($hub); ?>"><?php echo $hub->name; ?></a></h3>

      <div class="flex flex-wrap gap-1">
        <?php $status = get_field('status', $term); ?>
        <?php $status_color = get_status_tag($status); ?>
        <span class="badge badge-outline badge-<?php echo $status_color; ?> text-xs"><?php echo $status['label']; ?></span>

        <?php if(get_field('training', $term)) { ?>
          <span class="badge badge-outline badge-success text-xs"><?php _e('We Offer Training', 'tofino'); ?></span>
        <?php } ?>
      </div>

      <?php $hub_excerpt = strip_tags(get_field('hub_description', $term)); ?>

      <?php if($hub_excerpt && ($hub->slug != 'japan')) { ?>
        <p class="text-sm text-base-content/70"><?php echo get_words($hub_excerpt, 15); ?>...</p>
      <?php } ?>

      <a href="<?php echo get_term_link($hub); ?>" class="btn btn-outline btn-sm mt-auto">More</a>
    </div>
  </div>
</div>

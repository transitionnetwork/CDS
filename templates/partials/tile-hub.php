<?php $term = 'term_' . $hub->term_id; ?>

<div class="col-12 col-sm-6 col-lg-4 hub-col">
  <?php if(get_field('logo', $term)) {
    $url = get_field('logo', $term)['sizes']['large'];
  } else {
    $url = get_field('placeholder', 'options')['sizes']['large']; //default
  } ?>
  
  <div class="hub-item">
    <a class="thumbnail-bg" href="<?php echo get_term_link($hub); ?>" style="background-image: url(<?php echo $url; ?>)"></a>
    <div class="content">
      <h3 class="h2 mt-1"><a href="<?php echo get_term_link($hub); ?>"><?php echo $hub->name; ?></a></h3>
      
      <?php $status = get_field('status', $term); ?>
      <?php $status_color = get_status_tag($status); ?>
      <p><span class="btn-<?php echo $status_color; ?> btn-sm"><?php echo $status['label']; ?></span></p>
      
      <?php $hub_excerpt = strip_tags(get_field('hub_description', $term)); ?>

      
      <?php if($hub_excerpt && ($hub->slug != 'japan')) { ?>
        <p><?php echo get_words($hub_excerpt, 15); ?>...</p>
      <?php } ?>
        
      <p><a href="<?php echo get_term_link($hub); ?>" class="btn btn-outline">&raquo; More</a></p>
      
      <?php if(get_field('training', $term)) { ?>
        <p><span class="btn-sm btn-success"><?php _e('We Offer Training', 'tofino'); ?></span></p>
      <?php } ?>
    </div>
  </div>
</div>


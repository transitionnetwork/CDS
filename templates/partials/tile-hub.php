<?php $term = 'term_' . $hub->term_id; ?>

<div class="col-12 col-sm-6 col-md-4 col-lg-3 hub-col">
  <?php if(get_field('logo', $term)) {
    $url = get_field('logo', $term)['sizes']['large'];
  } else {
    $url = get_field('placeholder', 'options')['sizes']['large']; //default
  } ?>
  
  <div class="hub-item">
    <a class="thumbnail-bg" href="<?php echo get_term_link($hub); ?>" style="background-image: url(<?php echo $url; ?>)"></a>
    <div class="content">
      <h3><a href="<?php echo get_term_link($hub); ?>"><?php echo $hub->name; ?></a></h3>
      <p><strong>Status:</strong> <?php echo ucwords(get_field('status', $term)); ?></p>
      <?php $hub_excerpt = strip_tags(get_field('hub_description', $term)); ?>
      <?php if($hub_excerpt && ($hub->slug != 'japan')) { ?>
        <p><?php echo get_words($hub_excerpt, 15); ?>...</p>
      <?php } ?>
      <p class="mb-0"><a href="<?php echo get_term_link($hub); ?>">Read More</a></p>
    </div>
  </div>
</div>


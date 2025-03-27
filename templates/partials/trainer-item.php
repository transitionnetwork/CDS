<div class="col-12 col-md-6 col-lg-6">
  <div class="trainer-item">
    <?php $photo = (get_field('general_information_trainer_photo')) ? get_field('general_information_trainer_photo')['sizes']['large'] : get_field('placeholder', 'options')['sizes']['large']; ?>
    
    <a class="thumbnail-bg" href="<?php echo get_the_permalink(); ?>" style="background-image: url(<?php echo $photo; ?>)"></a>
    
    <div class="content">
      <h3 class="h2 mt-1"><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
      <?php if(is_user_trainer_admin()) {  ?>
        <p>
          <span class="btn btn-sm btn-<?php echo get_trainer_status(get_post_status())['color']; ?>"><?php echo get_trainer_status(get_post_status())['message']; ?></span>
        </p>
      <?php } ?>

      <?php
      $summary = get_words(get_field('general_information_trainer_bio'), 20);
      $summary = strrev(implode(strrev(''), explode(strrev('</p>'), strrev($summary), 2))); //remove last </p>
      ?>

      <p><?php echo strip_tags($summary); ?>&hellip;</p>

      <p><a href="<?php echo get_the_permalink(); ?>" class="btn btn-outline">More</a></p>

      <?php get_template_part('templates/partials/edit-trainer-button'); ?>
      <?php get_template_part('templates/partials/form-toggle-trainer-state'); ?>

    </div>
  </div>
</div>

<div class="col-12 col-md-6 col-lg-4">
  <div class="trainer-item">
    <?php $photo = (get_field('your_website_listing_training_photo')) ? get_field('your_website_listing_training_photo')['sizes']['large'] : get_field('placeholder', 'options')['sizes']['large']; ?>
    
    <a class="thumbnail-bg" href="<?php echo get_the_permalink(); ?>" style="background-image: url(<?php echo $photo; ?>)"></a>
    
    <div class="content">
      <h3><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
      <?php if(is_user_trainer_admin()) {  ?>
        <p>
          <span class="btn-sm btn-<?php echo get_trainer_status(get_post_status())['color']; ?>"><?php echo get_trainer_status(get_post_status())['message']; ?></span>
        </p>
      <?php } ?>

      <?php
      echo (get_words(get_field('your_website_listing_training_bio'), 20)); ?>&hellip;

      <p class="mb-0"><a href="<?php echo get_the_permalink(); ?>">&raquo; Read More</a></p>

      <?php get_template_part('templates/partials/edit-trainer-button'); ?>
      <?php get_template_part('templates/partials/form-toggle-trainer-state'); ?>

    </div>
  </div>
</div>

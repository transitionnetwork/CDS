<div class="trainer-item">
  <?php $photo = (get_field('general_information_trainer_photo')) ? get_field('general_information_trainer_photo')['sizes']['large'] : get_field('placeholder', 'options')['sizes']['large']; ?>

  <a class="thumbnail-bg" href="<?php echo get_the_permalink(); ?>" style="background-image: url(<?php echo $photo; ?>)"></a>

  <div class="content">
    <h3><a href="<?php the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>

    <?php if(is_user_trainer_admin()) { ?>
      <?php $trainer_status = get_trainer_status(get_post_status()); ?>
      <span class="badge badge-outline badge-<?php echo $trainer_status['color']; ?> text-xs"><?php echo $trainer_status['message']; ?></span>
    <?php } ?>

    <?php
    $summary = get_words(get_field('general_information_trainer_bio'), 20);
    $summary = strrev(implode(strrev(''), explode(strrev('</p>'), strrev($summary), 2)));
    ?>

    <p class="text-sm text-base-content/70"><?php echo strip_tags($summary); ?>&hellip;</p>

    <div class="flex flex-wrap gap-2">
      <?php get_template_part('templates/partials/edit-trainer-button'); ?>
      <?php get_template_part('templates/partials/form-toggle-trainer-state'); ?>
    </div>

    <a href="<?php echo get_the_permalink(); ?>" class="btn btn-outline btn-sm mt-auto w-full">More</a>
  </div>
</div>

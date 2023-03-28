<?php if($logo || $hubs || $topic) { ?>
  <div class="panel">
    <?php if(get_field('logo')) { ?>
      <img src="<?php echo get_field('logo')['sizes']['large']; ?>">
    <?php } ?>

    <?php if(count($hubs) === 1) { ?>
      <h3>Hub</h3>
      <a href="<?php echo get_term_link($hubs[0]); ?>"><?php echo $hubs[0]->name; ?></a>
    <?php } ?>
    <?php if($topics) { ?>
      <h3 class="mt-3">Topics</h3>
      <strong><?php echo get_taxonomy('topic')->label; ?>:</strong> <?php echo implode(', ', $topic_names); ?>
    <?php } ?>

    <?php if (get_field('email')) { ?>
      <h3 class="mt-3">Email</h3>
      <a href="mailto:<?php echo get_field('email'); ?>"><?php echo get_field('email'); ?></a>
    <?php } ?>

    <?php if(get_field('website') || get_field('facebook') || get_field('instagram') || get_field('twitter') || get_field('youtube')) { ?>
    <h3 class="mt-3"><?php _e('Links', 'tofino'); ?></h3>
    <ul class="links">
        <?php if (get_field('website')) { ?>
          <li><a href="<?php echo get_field('website'); ?>" target="_blank">Web</a></li>
        <?php } ?>
        <?php if (get_field('twitter')) { ?>
          <li><a href="<?php echo get_field('twitter'); ?>" target="_blank"><?php echo svg('twitter'); ?></a></li>
        <?php } ?>
        <?php if (get_field('facebook')) { ?>
          <li><a href="<?php echo get_field('facebook'); ?>" target="_blank"><?php echo svg('facebook'); ?></a></li>
        <?php } ?>
        <?php if (get_field('instagram')) { ?>
          <li><a href="<?php echo get_field('instagram'); ?>" target="_blank"><?php echo svg('instagram'); ?></a></li>
        <?php } ?>
        <?php if (get_field('youtube')) { ?>
          <li><a href="<?php echo get_field('youtube'); ?>" target="_blank"><?php echo svg('youtube'); ?></a></li>
        <?php } ?>
      </ul>
    <?php } ?>
    
    <?php $additional = get_field('additional_web_addresses'); 
    if($additional) { ?>
      <section>
        <h3 class="mt-3"><?php _e('More Links', 'tofino'); ?></h3>
        <ul>
          <?php foreach($additional as $item) { ?>
            <li><a href="<?php echo $item['address']; ?>" target="_blank"><?php echo $item['label']; ?></a></li>
          <?php } ?>
        </ul>
      </section>
    <?php } ?>
  </div>
<?php } ?>

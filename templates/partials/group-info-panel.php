<?php $hubs = get_the_terms($post, 'hub'); ?>
<?php $topics = get_the_terms($post, 'topic'); ?>

<?php if(get_field('logo') || $hubs || $topics) { ?>
  <div class="panel">
    <?php if(get_field('logo')) { ?>
      <img src="<?php echo get_field('logo')['sizes']['large']; ?>">
    <?php } ?>

    <?php if(count($hubs) === 1) { ?>
      <h3>Hub</h3>
      <a href="<?php echo get_term_link($hubs[0]); ?>"><?php echo $hubs[0]->name; ?></a>
    <?php } ?>
    
    <?php if($topics) { ?>
      <?php $topic_names = array(); ?>
      <?php foreach($topics as $topic) {
        $topic_names[] = $topic->name;
      } ?>
      
      <h3 class="mt-3">Topics</h3>
      <?php echo implode(', ', $topic_names); ?>
    <?php } ?>
  </div>
<?php } ?>

<div class="panel">
  <?php if (get_field('email')) { ?>
    <h3 class="mt-3">Email</h3>
    <a href="mailto:<?php echo get_field('email'); ?>"><?php echo get_field('email'); ?></a>
  <?php } ?>

  <?php
  $link_fields = array(
    'website',
    'twitter',
    'facebook',
    'instagram',
    'youtube',
  );

  $has_links = false;

  foreach($link_fields as $field) {
    if(get_field($field)) {
      $has_links = true;
    }
  } ?>

  <?php if($has_links) { ?>
    <div>
      <h3 class="mt-3"><?php _e('Links', 'tofino'); ?></h3>
      
      <ul class="links">
        <?php foreach($link_fields as $field) {
          if(get_field($field)) { ?>
            <li><a href="<?php echo get_field($field); ?>" target="_blank"><?php echo svg($field); ?></a></li>
          <?php } ?>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>

  <?php $additional = get_field('additional_web_addresses'); 
  if($additional) { ?>
    <div>
      <h3 class="mt-3"><?php _e('More Links', 'tofino'); ?></h3>
      <ul>
        <?php foreach($additional as $item) { ?>
          <li><a href="<?php echo $item['address']; ?>" target="_blank"><?php echo $item['label']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>
</div>

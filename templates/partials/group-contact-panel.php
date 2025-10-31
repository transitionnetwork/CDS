<?php $email = get_field('email'); ?>
<?php $additional = get_field('additional_web_addresses'); ?>
<?php
$link_fields = array(
  'website',
  'twitter',
  'facebook',
  'instagram',
  'youtube',
);

$reg_number = get_field('group_detail_registration_number');

$has_links = false;

foreach($link_fields as $field) {
  if(get_field($field)) {
    $has_links = true;
  }
} ?>

<div class="panel group-contact">
  <?php if ($email) { ?>
    <div class="mb-3">
      <h3>Email</h3>
      <a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a>
    </div>
  <?php } ?>

  <?php if($has_links) { ?>
    <div>
      <h3><?php _e('Links', 'tofino'); ?></h3>
      
      <ul class="links">
        <?php foreach($link_fields as $field) {
          if(get_field($field)) { ?>
            <li><a href="<?php echo get_field($field); ?>" target="_blank"><?php echo svg($field); ?></a></li>
          <?php } ?>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>
  
  <?php if($additional) { ?>
    <div class="mt-3">
      <h3><?php _e('More Links', 'tofino'); ?></h3>
      <ul>
        <?php foreach($additional as $item) { ?>
          <li><a href="<?php echo $item['address']; ?>" target="_blank"><?php echo $item['label']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>

  <?php if($reg_number) { ?>
    <div class="mt-3">
      <h3><?php _e('Registration Number', 'tofino'); ?></h3>
      <div><?php echo $reg_number; ?></div>
    </div>
  <?php } ?>
</div>

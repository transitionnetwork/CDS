<?php if($map) { ?>
  <div id="initiative-map" data-lat="<?php echo $map['lat']; ?>" data-lng="<?php echo $map['lng']; ?>" data-zoom="<?php echo $map['zoom']; ?>"></div>
<?php } ?>

<?php if (get_field('address_line_1')) { ?>
  <label><?php _e('Location', 'tofino'); ?></label>
  <?php echo get_field('address_line_1'); ?><br/>
  <?php echo get_field('city'); ?><br/>
  <?php echo get_field('province'); ?><br/>
  <?php echo get_field('postal_code'); ?><br/>
  <?php echo get_term_by('id', get_field('country'), 'country')->name; ?><br/>
  <div id="marker-address" data-address="<?php echo get_field('address_line_1'); ?>"></div>
<?php } else if ($map) { ?>
  <label><?php _e('Location', 'tofino'); ?></label>
  <div id="marker-address" data-address="<?php echo $map['markers'][0]['default_label']; ?>"></div>
  <?php echo $map['markers'][0]['default_label']; ?>
<?php } ?>
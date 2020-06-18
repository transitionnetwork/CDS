<div class="col-12 col-sm-6 col-md-4">
  <div class="file-tile">
    <?php $file = get_field('file'); ?>
    <?php if($file) { ?>
      <?php if($file['type'] == 'image') { ?>
        <p><img src="<?php echo $file['sizes']['thumbnail']; ?>" alt="<?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>"></p>
      <?php } else { ?>
        <?php var_dump($file['type']); ?>
      <?php } ?>
    <?php } ?>
    <h4><a href="<?php echo $file['url']; ?>" download><?php the_title(); ?></a></h4>
    <ul>
      <li><label>File Type:</label><?php echo $file['type']; ?></li>
      <?php if(get_field('license')) { ?>
        <li><label>License:</label><?php echo get_field('license'); ?></li>
      <?php } ?>
      <?php if(get_field('photographer')) { ?>
        <li><label>Photographer:</label><?php echo get_field('photographer'); ?></li>
      <?php } ?>
      <?php if(get_field('attribution')) { ?>
        <li><label>Attribution:</label><?php echo get_field('attribution'); ?></li>
      <?php } ?>
    </ul>
    <?php if(get_current_user_id() == get_the_author_meta('ID')) { ?>
      <a class="btn btn-sm btn-danger" href="<?php echo get_delete_post_link(); ?>" onclick="return confirm('<?php echo 'Are you sure?'; ?>')"><?php echo svg('trashcan'); ?><?php _e('Delete', 'tofino'); ?></a>
    <?php } ?>
  </div>
</div>

<?php

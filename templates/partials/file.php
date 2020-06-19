<div class="col-12 col-sm-6 col-md-3">
  <div class="file-tile" data-id="<?php echo get_the_ID(); ?>">
    <?php $file = get_field('file'); ?>
    <?php if($file) { ?>
      <?php if($file['type'] == 'image') { ?>
        <p><a href="<?php echo $file['url']; ?>" target="_blank"><img src="<?php echo $file['sizes']['thumbnail']; ?>" alt="<?php echo get_the_title(); ?>" title="<?php echo get_the_title(); ?>"></a></p>
      <?php } else { ?>
        <?php // insert preview icons; ?>
      <?php } ?>
    <?php } ?>
    <h4><a href="<?php echo $file['url']; ?>" target="_blank"><?php the_title(); ?></a></h4>
    <ul>
      <li><label><a class="download-file" href="<?php echo $file['url']; ?>" download>Download File</a> [<span class="download-count" data-value="<?php echo get_field('download_count') ? get_field('download_count') : 0; ?>"><?php echo get_field('download_count') ? get_field('download_count') : 0; ?></span>]</label></a>
      <li><label>File Type:</label><?php echo $file['type'] . '/' . $file['subtype']; ?></li>
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

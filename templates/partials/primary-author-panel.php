<div class="panel">
  <h3>Primary Editor</h3>
  <?php $post_author_id = get_the_author_meta('ID'); ?>
  <?php echo get_the_author_meta('display_name'); ?><br/>
  <a href="mailto:<?php echo get_the_author_meta('user_email'); ?>"><?php echo get_the_author_meta('user_email'); ?></a>
  
  <?php //get_template_part('templates/partials/update-primary-editor-id'); ?>
</div>

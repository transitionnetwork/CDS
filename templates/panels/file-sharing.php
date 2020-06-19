<?php acf_form_head(); ?>
<section>
  <h2>File Sharing</h2>
  <h3>My Files</h3>
  <?php $args = array(
    'post_type' => 'files',
    'posts_per_page' => -1,
    'author__in' => get_current_user_id()
  );
  $my_files = get_posts($args);
  if($my_files) { ?>
    <div class="row files-row">
      <?php foreach($my_files as $post) {
        setup_postdata($post);
        get_template_part('templates/partials/file');
      }
      wp_reset_postdata(); ?>
    </div>
  <?php } else { ?>
    <p>
      <?php _e('You haven\'t shared any files.', 'Tofino'); ?>
    </p>
  <?php } ?>
  <p><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFileModal"><?php echo svg('plus'); ?>Add a file</button></p>
  <hr></hr>
  <h3>All Files</h3>
  <?php $args = array(
    'post_type' => 'files',
    'posts_per_page' => -1,
  );
  $all_files = get_posts($args);
  if($all_files) { ?>
    <div class="row files-row">
      <?php foreach($all_files as $post) {
        setup_postdata($post);
        get_template_part('templates/partials/file');
      }
      wp_reset_postdata(); ?>
    </div>
  <?php } else { ?>
    <p>
      <?php _e('There are no files shared yet.', 'Tofino'); ?>
    </p>
  <?php } ?>
</section>

<!-- Modal -->
<div class="modal fade" id="addFileModal" tabindex="-1" role="dialog" aria-labelledby="addFileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addFileModalLabel">Add a new file</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?php acf_form(array(
          'post_id'		=> 'new_post',
          'post_title'	=> true,
          'post_content'	=> false,
          'return' => add_query_arg('tab', 'file', get_the_permalink()),
          'fields' => array('license', 'photographer', 'attribution', 'file'),
          'submit_value' => 'Add File',
          'uploader' => 'basic',
          'new_post'		=> array(
            'post_type'		=> 'files',
            'post_status' => 'publish'
          )
        ));
        ?>
      </div>
    </div>
  </div>
</div>

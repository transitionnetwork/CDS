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
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 files-row">
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
  <p><button type="button" class="btn btn-primary" onclick="document.getElementById('addFileModal').showModal()"><?php echo svg('plus'); ?>Add a file</button></p>
  <hr>
  <h3>All Files</h3>
  <?php $args = array(
    'post_type' => 'files',
    'posts_per_page' => -1,
  );
  $all_files = get_posts($args);
  if($all_files) { ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 files-row">
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
<dialog id="addFileModal" class="modal" aria-labelledby="addFileModalLabel">
  <div class="modal-box">
    <form method="dialog">
      <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2" aria-label="Close">✕</button>
    </form>
    <h3 class="text-lg font-bold" id="addFileModalLabel">Add a new file</h3>
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
  <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

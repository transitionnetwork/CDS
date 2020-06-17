<section>
  <h2>File Sharing</h2>
  <h3>My Files</h3>
  <?php $args = array(
    'post_type' => 'files',
    'posts_per_page' => -1,
    'author' => get_current_user_id()
  );
  $my_files = get_posts($args);
  var_dump($my_files);
  ?>
  <p><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addFileModal"><?php echo svg('plus'); ?>Add a file</button></p>
  <hr></hr>
  <h3>All Files</h3>
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
        ...
      </div>
    </div>
  </div>
</div>

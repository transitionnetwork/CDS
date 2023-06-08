<div class="panel grant-status">
  <h3>
    Grant Status
    
    <?php acf_form(array(
        'post_id'       => $post->ID,
        'post_title'    => false,
        'post_content'  => false,
        'field_groups' => array('group_6481cb63c3500'),
        'updated_message' => __('Status updated', 'acf'),
        'submit_value'  => __('Update')
    )); ?>
  </h3>
</div>

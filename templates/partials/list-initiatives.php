<?php $current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() ); ?>

<?php foreach($posts as $post) : ?>
  <?php setup_postdata($post); ?>
  <?php $author_object = get_user_by('id', get_the_author_id());
  $author_hub_name = get_the_terms($author_object, 'hub')[0]->name; ?>
  <div class="post-summary">
    <div>
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a><br/>
      <?php if($current_page->post_name != 'account') :
        if($author_hub_name) {
          echo '<em>hub: ' . $author_hub_name . '</em>';
        } else {
          echo '<em>no hub</em>';
        }
      endif; ?>
    </div>
    <div>
      <a class="btn btn-primary btn-sm" href="<?php the_permalink(); ?>">View</a>
      <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' ))) : ?>
        <?php $params = array('edit_post' => get_the_ID()); ?>
        <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit</a>
        <a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link( get_the_ID() ); ?>" onclick="return confirm('Are you sure you want to remove this hub?')">Delete</a>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>

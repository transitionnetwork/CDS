<?php
$user_role = wp_get_current_user()->roles[0];
$user_human_role = ucwords(str_replace('_', ' ', $user_role));
$user_hub = get_the_terms(wp_get_current_user(), 'hub');
$user_hub_name = $user_hub[0]->name;
$current_page = sanitize_post( $GLOBALS['wp_the_query']->get_queried_object() );
?>

<?php foreach($posts as $post) : ?>
  <?php setup_postdata($post); ?>

  <?php $author_object = get_user_by('id', get_the_author_meta('ID'));
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
      <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' ) || (get_super_hub_perms(get_the_author_meta('ID'))))) : ?>
        <?php $params = array('edit_post' => get_the_ID()); ?>
        <a class="btn btn-warning btn-sm" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit</a>
        <a class="btn btn-danger btn-sm" href="<?php echo get_delete_post_link( get_the_ID() ); ?>" onclick="return confirm('Are you sure you want to remove this hub?')">Delete</a>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>

<?php if(!$posts) { ?>
  There aren't any initiatives yet
<?php } ?>
  
<?php wp_reset_postdata(); ?>

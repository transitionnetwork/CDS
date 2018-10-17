<main>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <h2>General Information <span style="color: green;">(public)</span></h2>
          <?php $map_array = get_field('map', get_the_ID(), false); ?>
          <?php echo get_field('map'); ?>
          
          <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' ))) : ?>
            <h2>Email and Health Check Results Information <span style="color: red;">(private)</span></h2>
            <?php the_field('private_field'); ?>
          <?php else : ?>
            <strong style="color: red;">(Email and Health Check Results are hidden)</strong>
          <?php endif; ?>

          <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' ))) : ?>
            <?php $params = array('edit_post' => get_the_ID()); ?>
            <div class="button-block"><a class="btn btn-warning" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit this initiative</a></div>
            <div class="button-block"><a class="btn btn-danger" href="<?php echo get_delete_post_link(get_the_ID()); ?>">Delete this initiative</a></div>
          <?php endif; ?>

        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

<main>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?></h1>
          <h2>General Information <span style="color: green;">(public)</span></h2>
          <?php the_field('public_field'); ?>
          
          <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' ))) : ?>
            <h2>Email and Health Check Results Information <span style="color: red;">(private)</span></h2>
            <?php the_field('private_field'); ?>
          <?php else : ?>
            <strong style="color: red;">(Email and Health Check Results are hidden)</strong>
          <?php endif; ?>

          <?php if((get_the_author_meta('ID') == get_current_user_id()) || (current_user_can( 'manage_options' ))) : ?>
            <?php $params = array('edit_post' => get_the_ID()); ?>
            <a class="btn btn-warning" href="<?php echo add_query_arg($params, '/edit-initiative'); ?>">Edit this initiative</a>
          <?php endif; ?>

        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

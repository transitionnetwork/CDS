<main>
  <div class="container">
    <div class="row">
      <div class="col-12">
        <?php while (have_posts()) : the_post(); ?>
          <h1><?php echo \Tofino\Helpers\title(); ?> - <?php echo get_the_terms(wp_get_current_user(), 'hub')[0]->name; ?></h1>
          <?php $user_hub_id = get_the_terms(wp_get_current_user(), 'hub')[0]->term_id; 
          $hub_user_ids = get_objects_in_term($user_hub_id, 'hub');
          $other_hub_user_ids = array_diff($hub_user_ids, array(wp_get_current_user()->ID));
          if($other_hub_user_ids) { ?>
            <table class="item-list">
              <tr>
                <th>Display Name</th>
                <th>Email</th>
              </tr>
              <?php foreach($other_hub_user_ids as $user_id) :
                $user = get_user_by('ID', $user_id); ?>
                <?php if ($user) { ?>
                  <tr>
                    <td>
                      <a href="#"><?php echo $user->data->display_name; ?></a>
                    </td>
                    <td>
                      <?php echo $user->data->user_email; ?>
                    </td>
                  </div>
                <?php } ?>
              <?php endforeach; ?>
            </table>
          <?php } else { ?>
            <p>There are no other users</p>
          <?php } ?>
        <ul class="button-group">
          <li><a href="<?php the_permalink(24); ?>" class="btn btn-primary">&laquo Back to dashboard</a></li>
        </ul> 
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</main>

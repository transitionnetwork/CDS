<?php if(is_user_role(array('super_hub', 'administrator')) || can_edit_hub($term->term_id)) { ?>
    <div class="mt-5">
      <h3>Contact all groups in <?php echo \Tofino\Helpers\title(); ?></h3>

      <?php $email_addresses = array() ?>
      <?php $args = array(
        'post_type' => 'initiatives',
        'fields' => 'ids',
        'posts_per_page' => -1,
        'tax_query' => array(
          'AND',
          array(
            'taxonomy' => 'hub',
            'field' => 'term_id',
            'terms' => $term->term_id
          )
        )
      );
      $posts = get_posts($args);
      if($posts) {
        foreach($posts as $post) {
          setup_postdata( $post );
          if(get_field('email')) {
            $email_addresses[] = get_field('email');
          }

          if (filter_var(get_the_author_meta('user_email'), FILTER_VALIDATE_EMAIL)) {
            $email_addresses[] = get_the_author_meta('user_email');
          }

          $co_authors = ma_get_co_authors($post->ID);
          if($co_authors) {
            foreach($co_authors as $user_id) {
              if (filter_var(get_userdata($user_id)->user_email, FILTER_VALIDATE_EMAIL)) {
                $email_addresses[] = get_userdata($user_id)->user_email;
              }
            }
          }
        }
        wp_reset_postdata();
      } ?>
      <?php $email_addresses = array_unique($email_addresses); ?>
      <?php $email_addresses = array_change_key_case($email_addresses, CASE_LOWER); ?>
      <?php var_dump($email_addresses); ?>
      <div id="group-name" class="" data-name="Member of <?php echo strip_tags( \Tofino\Helpers\title()); ?>"></div>
      <div id="group-email" class="" data-email="<?php echo implode(', ', $email_addresses); ?>"></div>
      <form action="<?php echo get_the_permalink(); ?>" method="POST">
        <div>
          <label for="hub_email_subject">Subject</label><br>
          <input type="text" name="hub_email_subject" id="hub_email_subject">
        </div>
        <div>
          <label for="hub_email_message">Message</label><br>
          <textarea name="hub_email_message" id="hub_email_message"></textarea>
        </div>
        <input type="hidden" name="hub_email_source" value="<?php echo \Tofino\Helpers\title(); ?>">
        <button type="submit" name="hub_email_submit" value="Submit" class="btn btn-danger btn-sm">Send Email</button>
      </form>
    </div>
  <?php } ?>

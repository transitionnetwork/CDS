<?php $term = get_queried_object(); ?>

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
      $post_ids = get_posts($args);
      if($post_ids) {
        foreach($post_ids as $post_id) {
          if(get_field('email', $post_id)) {
            $email_addresses[] = get_field('email', $post_id);
          }

          $email_addresses[] = get_the_author_meta('user_email', get_post_field('post_author', $post_id));

          $co_authors = ma_get_co_authors($post_id);
          if($co_authors) {
            foreach($co_authors as $user_id) {
              $email_addresses[] = get_userdata($user_id)->user_email;
            }
          }
        }
      } ?>
      <?php $email_addresses = array_unique($email_addresses); ?>
      <div id="group-name" class="" data-name="Member of <?php echo strip_tags( \Tofino\Helpers\title()); ?>"></div>
      <div id="group-email" class="" data-email="<?php echo implode(', ', $email_addresses); ?>"></div>
      <?php echo do_shortcode('[contact-form-7 id="2ab2678" title="Hub Contact Form"]'); ?>
    </div>
  <?php } ?>

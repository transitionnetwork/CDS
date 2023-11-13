  <div class="container">
    <div class="alert top alert-info">
      <?php if (wp_get_current_user()->roles[0] === 'initiative') {
        _e('Thank you for your submission. It is now awaiting approval by a hub user.', 'tofino');
      } else {
        _e('Thank you for your submission', 'tofino');
      } ?>

      <?php $hub = get_the_terms($post, 'hub')[0]->term_id; ?>
      <?php if(in_array($hub, get_tt_hub_ids())) { ?>
        <div class="mt-2">
          <?php echo apply_filters('the_content', get_post_field('post_content', 9101)); ?>
        </div>
      <?php } ?>
    </div>
  </div>

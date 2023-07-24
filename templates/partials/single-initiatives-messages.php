<?php $updated = get_query_var('updated'); ?>

<?php if($updated) { ?>
  <?php if($updated === 'healthcheck') { ?>
    <div class="container">
      <div class="alert top alert-success">
       <?php echo get_post_field('post_content', 45); ?>
      </div>
    </div>
  <?php } ?>
  
  <?php if($updated === 'author') { ?>
    <div class="container">
      <div class="alert top alert-success">
        <?php _e('The author of this group has been updated. The new author has been emailed', 'tofino'); ?>
        <?php custom_email_initaitive_author_updated(get_the_ID()) ?>
      </div>
    </div>
  <?php } ?>
<?php } ?>

<?php if(get_query_var('edited_post')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('This group has been updated', 'tofino'); ?>

      <?php $hub = get_the_terms($post, 'hub')[0]->term_id; ?>
      <?php if(in_array($hub, get_tt_hub_ids())) { ?>
        <div class="mt-2">
          <?php echo apply_filters('the_content', get_post_field('post_content', 9101)); ?>
        </div>
      <?php } ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('added_post')) { ?>
  <div class="container">
    <div class="alert top alert-success">
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
<?php } ?>

<?php if(get_query_var('added_note')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('Your note has been added', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('edited_note')) { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('Your note has been updated', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('deleted') === 'co_author') { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('Co-Author has been removed', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

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
      <div class="mt-2">
        <?php if(in_array($hub, get_tt_hub_ids())) {
          echo get_field('hub_add_tt', 'options');
        } else {
          echo get_field('hub_add_row', 'options');
        } ?>
      </div>
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
      <?php _e('Co-author has been removed', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('promoted') === 'co_author') { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('Co-author has been promoted to primary author', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('added') === 'co_author') { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('The email is already registered on <a href="https://transitiongroups.org">transitiongroups.org</a> and has been added as a co-author to the group', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('added') === 'login_token_generated') { ?>
  <div class="container">
    <div class="alert top alert-success">
      <?php _e('A login token has been generated for the primary author', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('failed') === 'co_author') { ?>
  <div class="container">
    <div class="alert top alert-danger">
      <?php _e('The email is already the primary email of the gropup and cannot be added as a co-author', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

<?php if(get_query_var('added') === 'co_author_invited') { ?>
  <div class="container">
    <div class="alert top alert-info">
      <?php _e('The requested email is not currently registered on <a href="https://transitiongroups.org">transitiongroups.org</a>. We have invited them to join the site in order to be added as a co-author to this group', 'tofino'); ?>
    </div>
  </div>
<?php } ?>

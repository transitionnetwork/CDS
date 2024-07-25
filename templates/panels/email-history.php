<div class="panel">
  <h3>Email History</h3>
  <?php $email_log = get_post_meta( $post->ID, 'mail_log', true); ?>

  <?php if($email_log) { ?>
    <?php $email_log = array_reverse($email_log); ?>
    <div class="panel">
      <div class="mb-3">
        <strong>Last Event:</strong> <?php echo get_post_meta($post->ID, 'last_mail_event', true); ?>
      </div>
      <div id="email-log">
        <?php foreach($email_log as $item) { ?>
          <?php echo $item; ?></br>
        <?php } ?>
      </div>
    </div>

  <?php } else { ?>
    <?php _e('There is no email history'); ?>
  <?php } ?>
</div>

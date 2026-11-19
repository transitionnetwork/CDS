<?php $term = get_queried_object(); ?>

<?php if(is_user_role(array('super_hub', 'administrator')) || can_edit_hub($term->term_id)) { ?>
    <div class="mt-12">
      <h3>Contact all groups in <?php echo \Tofino\Helpers\title(); ?></h3>

      <?php $email_addresses = get_hub_contact_emails($term->term_id); ?>
      <div id="group-name" class="" data-name="Member of <?php echo strip_tags( \Tofino\Helpers\title()); ?>"></div>
      <div id="group-email" class="" data-email="<?php echo implode(', ', $email_addresses); ?>"></div>
      <?php echo do_shortcode('[contact-form-7 id="2ab2678" title="Hub Contact Form"]'); ?>
    </div>
  <?php } ?>

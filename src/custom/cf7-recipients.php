<?php
/**
 * Server-side recipients for Contact Form 7 forms.
 *
 * The group, trainer and hub contact forms take the recipient address from a
 * hidden field that JS fills in. That field comes from the browser, so it
 * can't be trusted: anyone could POST any address and use our mail server to
 * send to it. Here we throw away the posted value and look the recipient up
 * from the post or hub the form was shown on.
 *
 * If no valid recipient can be found the send is aborted (see
 * cds_cf7_abort_without_recipient). CF7's own required-field check can't do
 * this for us because it validates the raw $_POST, not the filtered data.
 */

const CDS_CF7_GROUP_FORM   = 8907;
const CDS_CF7_TRAINER_FORM = 8688;
const CDS_CF7_HUB_FORM     = 14877;

/**
 * Collect every contact address for the groups in a hub: the group email,
 * the author and any co-authors.
 */
function get_hub_contact_emails($term_id) {
  $email_addresses = array();

  $post_ids = get_posts(array(
    'post_type'      => 'initiatives',
    'fields'         => 'ids',
    'posts_per_page' => -1,
    'tax_query'      => array(
      array(
        'taxonomy' => 'hub',
        'field'    => 'term_id',
        'terms'    => $term_id
      )
    )
  ));

  foreach ($post_ids as $post_id) {
    if (get_field('email', $post_id)) {
      $email_addresses[] = get_field('email', $post_id);
    }

    $email_addresses[] = get_the_author_meta('user_email', get_post_field('post_author', $post_id));

    $co_authors = ma_get_co_authors($post_id);
    if ($co_authors) {
      foreach ($co_authors as $user_id) {
        $user = get_userdata($user_id);
        if ($user) {
          $email_addresses[] = $user->user_email;
        }
      }
    }
  }

  return array_values(array_unique(array_filter($email_addresses)));
}

/**
 * Add the hub term ID to the hub contact form, because hub archive pages
 * have no container post. It's still a browser value, so the submit handler
 * checks the user is allowed to email that hub.
 */
function cds_cf7_hub_hidden_field($fields) {
  $form = WPCF7_ContactForm::get_current();

  if ($form && (int) $form->id() === CDS_CF7_HUB_FORM && is_tax('hub')) {
    $fields['_cds_hub_id'] = get_queried_object_id();
  }

  return $fields;
}
add_filter('wpcf7_form_hidden_fields', 'cds_cf7_hub_hidden_field');

/**
 * The container post if it's a published post of the given type, or one the
 * current user can edit (so admins can test on pending posts).
 */
function cds_cf7_container_post($post_type) {
  $post_id = isset($_POST['_wpcf7_container_post']) ? absint($_POST['_wpcf7_container_post']) : 0;

  if (!$post_id || get_post_type($post_id) !== $post_type) {
    return false;
  }

  if (get_post_status($post_id) !== 'publish' && !current_user_can('edit_post', $post_id)) {
    return false;
  }

  return $post_id;
}

/**
 * Replace the recipient name and email in the posted data with values from
 * the server. This runs before validation and before mail tags are replaced,
 * so the email headers, body and Flamingo entry all get these values.
 */
function cds_cf7_server_side_recipients($posted_data) {
  $form = WPCF7_ContactForm::get_current();
  if (!$form) {
    return $posted_data;
  }

  switch ((int) $form->id()) {
    case CDS_CF7_GROUP_FORM:
      $post_id = cds_cf7_container_post('initiatives');

      $posted_data['group-name']  = $post_id ? get_the_title($post_id) : '';
      $posted_data['group-email'] = $post_id ? sanitize_email(get_field('email', $post_id)) : '';
      break;

    case CDS_CF7_TRAINER_FORM:
      $post_id = cds_cf7_container_post('trainers');

      $posted_data['trainer-name']  = $post_id ? get_the_title($post_id) : '';
      $posted_data['trainer-email'] = $post_id ? sanitize_email(get_field('general_information_email', $post_id)) : '';
      break;

    case CDS_CF7_HUB_FORM:
      $term_id = isset($_POST['_cds_hub_id']) ? absint($_POST['_cds_hub_id']) : 0;
      $term    = $term_id ? get_term($term_id, 'hub') : null;

      if ($term && !is_wp_error($term) && is_user_logged_in() && can_edit_hub($term_id)) {
        $posted_data['group-name']  = 'Member of ' . $term->name;
        $posted_data['group-email'] = implode(', ', get_hub_contact_emails($term_id));
      } else {
        $posted_data['group-name']  = '';
        $posted_data['group-email'] = '';
      }
      break;
  }

  return $posted_data;
}
add_filter('wpcf7_posted_data', 'cds_cf7_server_side_recipients');

/**
 * Stop the send if the server couldn't work out a recipient.
 */
function cds_cf7_abort_without_recipient($contact_form, &$abort, $submission) {
  $recipient_fields = array(
    CDS_CF7_GROUP_FORM   => 'group-email',
    CDS_CF7_TRAINER_FORM => 'trainer-email',
    CDS_CF7_HUB_FORM     => 'group-email',
  );

  $form_id = (int) $contact_form->id();

  if (isset($recipient_fields[$form_id]) && empty($submission->get_posted_data($recipient_fields[$form_id]))) {
    $abort = true;
    $submission->set_response(__('Sorry, this message could not be sent. Please email websupport@transitionnetwork.org.', 'tofino'));
  }
}
add_action('wpcf7_before_send_mail', 'cds_cf7_abort_without_recipient', 10, 3);

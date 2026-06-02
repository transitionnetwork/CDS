<?php
/**
 * Greylist one-off migration / backfill.
 *
 * Run (idempotent, safe to re-run):
 *   ddev wp eval-file wp-content/themes/tofino/scripts/greylist-backfill.php
 *   # production:  wp eval-file wp-content/themes/tofino/scripts/greylist-backfill.php
 *
 * Does two things:
 *   1. Populates the new "Greylist Countries" options field with the country
 *      term IDs that were previously hardcoded in gl_additional_info's
 *      conditional logic. Written by ACF field KEY, so it works from the local
 *      acf-json definition with no DB field-group sync required.
 *   2. Backfills the `greylist_info_provided` flag on existing groups that have
 *      already supplied greylist info, so they are not re-prompted on the edit
 *      screen. The two uploads are offloaded to Dropbox and deleted, so we
 *      detect prior completion via the persistent text sub-fields.
 */

if (!defined('WP_CLI') || !WP_CLI) {
  die("This script must be run via 'wp eval-file'.\n");
}

// Pass "dry-run" to preview without writing anything:
//   wp eval-file wp-content/themes/tofino/scripts/greylist-backfill.php dry-run
$dry_run = isset($args) && in_array('dry-run', (array) $args, true);
if ($dry_run) {
  WP_CLI::log('--- DRY RUN: no changes will be written ---');
}

// ---------------------------------------------------------------------------
// 1. Greylist Countries options field
// ---------------------------------------------------------------------------

$greylist_country_ids = array(61, 62, 106, 126, 132, 134, 157, 164, 166, 218, 227, 251, 252, 253, 257);

// NOTE: do NOT use update_field() with the field KEY here. greylist_countries is
// a sub-field of the "greylist" group, so ACF reads it from the group-prefixed
// meta key `options_greylist_greylist_countries` (mirroring greylist_admin_email).
// update_field() by key writes the un-prefixed `options_greylist_countries`,
// which get_field('greylist_greylist_countries', ...) never reads. Write the
// nested value + field reference directly so the read path lines up.
if (!$dry_run) {
  update_option('options_greylist_greylist_countries', $greylist_country_ids);
  update_option('_options_greylist_greylist_countries', 'field_6904d0e10c7ad');

  // Remove any stray un-prefixed keys left by a previous update_field()-by-key run.
  delete_option('options_greylist_countries');
  delete_option('_options_greylist_countries');

  $saved = get_field('greylist_greylist_countries', 'options') ?: array();
  WP_CLI::log('Greylist Countries option set to ' . count($saved) . ' countries: ' . implode(', ', array_map('intval', $saved)));
} else {
  WP_CLI::log('Would set Greylist Countries option to ' . count($greylist_country_ids) . ' countries: ' . implode(', ', $greylist_country_ids));
}

// ---------------------------------------------------------------------------
// 2. Backfill greylist_info_provided on already-compliant groups
// ---------------------------------------------------------------------------

$groups = get_posts(array(
  'post_type'      => 'initiatives',
  'post_status'    => 'any',
  'posts_per_page' => -1,
  'fields'         => 'ids',
));

// Persistent sub-fields that indicate the greylist info was filled in
// (uploads are excluded because they are deleted after Dropbox offload).
$indicator_fields = array('date_of_birth', 'phone_number', 'addiitional_name', 'additional_email');

$flagged  = 0;
$skipped  = 0;
$no_info  = 0;

foreach ($groups as $group_id) {
  if (get_post_meta($group_id, 'greylist_info_provided', true)) {
    $skipped++;
    continue; // already flagged
  }

  $info     = get_field('gl_additional_info', $group_id);
  $provided = false;

  if (is_array($info)) {
    foreach ($indicator_fields as $key) {
      if (!empty($info[$key])) {
        $provided = true;
        break;
      }
    }
  }

  if ($provided) {
    if (!$dry_run) {
      update_post_meta($group_id, 'greylist_info_provided', current_time('mysql'));
    }
    $flagged++;
    WP_CLI::log(sprintf('%sFlagged group %d — %s', $dry_run ? '[dry-run] would flag: ' : '', $group_id, get_the_title($group_id)));
  } else {
    $no_info++;
  }
}

WP_CLI::success(sprintf(
  'Backfill complete. Flagged: %d | Already flagged: %d | No greylist info: %d | Total groups: %d',
  $flagged,
  $skipped,
  $no_info,
  count($groups)
));

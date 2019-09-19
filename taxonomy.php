<?php
/**
 *
 * Taxonomy template
 *
 * @package Tofino
 */

use \Tofino\Helpers as h;

$template = h\get_page_name();

get_header();

$taxonomy = get_queried_object()->taxonomy;

if (locate_template('templates/content-taxonomy-' . $taxonomy . '.php') != '') {
  get_template_part('templates/content-taxonomy', $taxonomy); // e.g. templates/content-page-members.php
}

get_footer(); ?>

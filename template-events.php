<?php
/**
 * Template Name: Xinc Events Iframe Page
 */

get_header('bare');

$api_url = get_field('pretix_api_url', $post->post_parent);
$api_key = get_field('pretix_api_key', $post->post_parent);

$events = xinc_events_get_events($api_url, $api_key);
get_template_part('templates/partials/events/table-events', null, array('events' => $events));

get_footer('bare');

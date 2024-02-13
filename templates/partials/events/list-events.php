<?php $api_url = get_field('pretix_api_url'); ?>
<?php $api_key = get_field('pretix_api_key'); ?>

<?php $events = xinc_events_get_events($api_url, $api_key); ?>
<?php get_template_part('templates/partials/events/table-events', null, array('events' => $events)); ?>

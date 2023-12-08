<?php $events = xinc_events_get_events('https://pretix.eu/api/v1/organizers/transition-network/events'); ?>

<?php get_template_part('templates/partials/events/table-events', null, array('events' => $events)); ?>

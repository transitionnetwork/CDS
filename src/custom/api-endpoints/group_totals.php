<?php
function get_group_totals_from_request($request) {
  $args['view'] = 'list';
  $totals = get_group_totals($args);
  return array(
    'body' => $totals
  );
}

function endpoint_get_group_totals(WP_REST_Request $request) {
  return get_group_totals_from_request($request);
}

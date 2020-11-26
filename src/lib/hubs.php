<?php 
//This is currently not in use.
function get_hub_marker_ids() {
  $hubs = get_terms('hub');
  foreach($hubs as $hub) {
    $map = get_field('map', $hub);
    if($map) {
      var_dump($hub);
      var_dump($map['markers']);
    }
  }
}

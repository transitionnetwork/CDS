<?php 
function get_status_tag($status) {
  switch($status['value']) {
    case 'forming' :
    case 're-forming' :
      return 'warning';
      break;
    case 'functioning' :
      return 'primary';
      break;
    case 'unknown' :
    case 'no-hub' :
      return 'secondary';
    default: 
      break;
  }
}

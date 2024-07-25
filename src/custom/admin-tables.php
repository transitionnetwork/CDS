<?php
function new_modify_user_table( $columns ) {
    unset($columns['posts']);
    $columns['hub'] = 'Hub';
    $columns['id'] = 'User ID';
    return $columns;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'hub' :
            $hub_ids = get_field('hub_user', 'user_' . $user_id);
            if(!is_array($hub_ids)) {
                $hub_ids[] = $hub_ids;
            }
            
            $output_hubs = [];
            foreach($hub_ids as $id) {
                $hub = get_term($id, 'hub');
                if($hub && !is_wp_error($hub)) {
                    $output_hubs[] = $hub->name;
                }
            }

            return implode(', ', $output_hubs);
        case 'id' :
            return $user_id;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

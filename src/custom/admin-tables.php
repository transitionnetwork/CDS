<?php
function new_modify_user_table( $columns ) {
    unset($columns['posts']);
    $columns['hub'] = 'Hub';
    return $columns;
}
add_filter( 'manage_users_columns', 'new_modify_user_table' );

function new_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'hub' :
            $hub_id = get_user_meta( $user_id, 'hub_user')[0];
            return get_term($hub_id, 'hub')->name;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

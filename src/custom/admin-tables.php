<?php
//USERS
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
            $hub_ids = get_user_meta( $user_id, 'hub_user');
            if(is_array($hub_ids)) {
                $output_hubs = array();
                foreach($hub_ids as $id) {
                    $hub = get_term($id, 'hub');
                    if($hub && !is_wp_error($hub)) {
                        $output_hubs[] = $hub->name;
                    }
                }

                return implode(', ', $output_hubs);
            }
        case 'id' :
            return $user_id;
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row', 10, 3 );

//GROUP FILTER
function filter_backend_by_taxonomies( $post_type, $which ) {

    // Apply initiatives to a specific CPT
    if ( 'initiatives' !== $post_type )
        return;

    // A list of custom taxonomy slugs to filter by
    $taxonomies = array( 'hub', 'country' );

    foreach ( $taxonomies as $taxonomy_slug ) {

        // Retrieve taxonomy data
        $taxonomy_obj = get_taxonomy( $taxonomy_slug );
        $taxonomy_name = $taxonomy_obj->labels->name;

            // Retrieve taxonomy terms
        $terms = get_terms( $taxonomy_slug );

        // Display filter HTML
        echo "<select name='{$taxonomy_slug}' id='{$taxonomy_slug}' class='postform'>";
        echo '<option value="">' . sprintf( esc_html__( 'Show All %s', 'text_domain' ), $taxonomy_name ) . '</option>';
        foreach ( $terms as $term ) {
            printf(
                '<option value="%1$s" %2$s>%3$s (%4$s)</option>',
                $term->slug,
                ( ( isset( $_GET[$taxonomy_slug] ) && ( $_GET[$taxonomy_slug] == $term->slug ) ) ? ' selected="selected"' : '' ),
                $term->name,
                $term->count
            );
        }
        echo '</select>';
    }
}

add_action( 'restrict_manage_posts', 'filter_backend_by_taxonomies' , 99, 2);

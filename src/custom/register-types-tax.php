<?php
//Custom Post Types
function create_posttypes() {
  register_post_type(
    'initiatives',
    array(
      'labels' => array(
        'name' => __('Groups'),
        'singular_name' => __('Group')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title', 'editor', 'author')
    )
  );

  register_post_type(
    'trainers',
    array(
      'labels' => array(
        'name' => __('Trainers'),
        'singular_name' => __('Trainer')
      ),
      'public' => true,
      'has_archive' => 'trainers',
      'supports' => array('title', 'author')
    )
  );

  register_post_type(
    'healthchecks',
    array(
      'labels' => array(
        'name' => __('Healthchecks'),
        'singular_name' => __('Healthcheck')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title')
    )
  );

  register_post_type(
    'hub_applications',
    array(
      'labels' => array(
        'name' => __('Hub Applications'),
        'singular_name' => __('Hub Application')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title')
    )
  );
  
  register_post_type(
    'files',
    array(
      'labels' => array(
        'name' => __('Files'),
        'singular_name' => __('File')
      ),
      'public' => true,
      'has_archive' => false,
      'supports' => array('title')
    )
  );

  register_post_type(
    'emails',
    array(
      'labels' => array(
        'name' => __('Emails'),
        'singular_name' => __('Email')
      ),
      'show_in_nav_menus' => false,
      'publicly_queryable' => false,
      'has_archive' => false,
      'show_ui' => true,
      'supports' => array('title', 'editor')
    )
  );
}
add_action('init', 'create_posttypes');


// Create user taxonomies
function create_custom_taxonomies() {
  $args = array(
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'labels' => array(
      'name'                      => 'Hubs',
      'singular_name'             => 'Hub',
      'menu_name'                 => 'Hubs',
      'search_items'              => 'Search Hubs',
      'popular_items'             => 'Popular Hubs',
      'all_items'                 => 'All Hubs',
      'edit_item'                 => 'Edit Hub',
      'update_item'               => 'Update Hub',
      'add_new_item'              => 'Add New Hub',
      'new_item_name'             => 'New Hub Name',
      'separate_items_with_commas' => 'Separate hubs with commas',
      'add_or_remove_items'       => 'Add or remove hubs',
      'choose_from_most_used'     => 'Choose from the most popular hubs',
    )
  );

  register_taxonomy('hub', array('initiatives', 'trainers'), $args);
 
  $args = array(
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'topic', 'with_front' => false),
    'labels' => array(
      'name' => _x('Topics', 'taxonomy general name'),
      'singular_name' => _x('Topic', 'taxonomy singular name'),
      'search_items' => __('Search Topics'),
      'all_items' => __('All Topics'),
      'parent_item' => __('Parent Topic'),
      'parent_item_colon' => __('Parent Topic:'),
      'edit_item' => __('Edit Topic'),
      'update_item' => __('Update Topic'),
      'add_new_item' => __('Add New Topic'),
      'new_item_name' => __('New Topic Name'),
      'menu_name' => __('Topic'),
    )
  );
  register_taxonomy('topic', array('initiatives'), $args);

  $args = array(
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'country', 'with_front' => false),
    'labels' => array(
      'name' => _x('Countries', 'taxonomy general name'),
      'singular_name' => _x('Country', 'taxonomy singular name'),
      'search_items' => __('Search Countries'),
      'all_items' => __('All Countries'),
      'parent_item' => __('Parent Country'),
      'parent_item_colon' => __('Parent Country:'),
      'edit_item' => __('Edit Country'),
      'update_item' => __('Update Country'),
      'add_new_item' => __('Add New Country'),
      'new_item_name' => __('New Country Name'),
      'menu_name' => __('Country'),
    )
  );

  register_taxonomy('country', array('initiatives', 'trainers'), $args);

  $args = array(
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'language', 'with_front' => false),
    'labels' => array(
      'name' => _x('Languages', 'taxonomy general name'),
      'singular_name' => _x('Language', 'taxonomy singular name'),
      'search_items' => __('Search Languages'),
      'all_items' => __('All Languages'),
      'parent_item' => __('Parent Language'),
      'parent_item_colon' => __('Parent Language:'),
      'edit_item' => __('Edit Language'),
      'update_item' => __('Update Language'),
      'add_new_item' => __('Add New Language'),
      'new_item_name' => __('New Language Name'),
      'menu_name' => __('Languages'),
    )
  );

  register_taxonomy('trainer_language', array('trainers'), $args);

  $args = array(
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'type', 'with_front' => false),
    'labels' => array(
      'name' => _x('Types', 'taxonomy general name'),
      'singular_name' => _x('Type', 'taxonomy singular name'),
      'search_items' => __('Search Types'),
      'all_items' => __('All Types'),
      'parent_item' => __('Parent Type'),
      'parent_item_colon' => __('Parent Type:'),
      'edit_item' => __('Edit Type'),
      'update_item' => __('Update Type'),
      'add_new_item' => __('Add New Type'),
      'new_item_name' => __('New Type Name'),
      'menu_name' => __('Types'),
    )
  );

  register_taxonomy('trainer_type', array('trainers'), $args);

  $args = array(
    'hierarchical' => true,
    'show_ui' => true,
    'show_admin_column' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'trainer-topic', 'with_front' => false),
    'labels' => array(
      'name' => _x('Trainer Topics', 'taxonomy general name'),
      'singular_name' => _x('Trainer Topic', 'taxonomy singular name'),
      'search_items' => __('Search Trainer Topics'),
      'all_items' => __('All Trainer Topics'),
      'parent_item' => __('Parent Trainer Topic'),
      'parent_item_colon' => __('Parent Trainer Topic:'),
      'edit_item' => __('Edit Trainer Topic'),
      'update_item' => __('Update Trainer Topic'),
      'add_new_item' => __('Add New Trainer Topic'),
      'new_item_name' => __('New Trainer Topic Name'),
      'menu_name' => __('Trainer Topics'),
    )
  );

  register_taxonomy('trainer_topic', array('trainers'), $args);
}
add_action('init', 'create_custom_taxonomies');

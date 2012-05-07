<?php

add_action('init', 'pattern_register');
 
function pattern_register() {
 
	$labels = array(
		'name' => _x('Patterns', 'post type general name'),
		'singular_name' => _x('Pattern', 'post type singular name'),
		'add_new' => _x('Add New', 'portfolio item'),
		'add_new_item' => __('Add New Pattern'),
		'edit_item' => __('Edit Pattern'),
		'new_item' => __('New Pattern'),
		'view_item' => __('View Pattern'),
		'search_items' => __('Search Patterns'),
		'not_found' =>  __('No patterns found'),
		'not_found_in_trash' => __('No patterns found in Trash'),
		'parent_item_colon' => ''
	);
 
	$args = array(
		'labels' => $labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','thumbnail')
	  ); 
 
	register_post_type( 'pattern' , $args );
}

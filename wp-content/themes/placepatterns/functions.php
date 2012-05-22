<?php
add_action('init', 'types_register');
add_action( 'wp_loaded', 'connections_register' );
add_filter( 'pre_get_posts', 'my_get_posts' );
add_action('init', 'menus_register');
add_action('init', 'taxonomies_register');
add_action('wp_loaded', 'add_widgets');
add_action('init', 'fields_register');

function fields_register() {

	//Custom field for google map
	if (function_exists('register_field')) {
		register_field('Location_field', dirname(__FILE__) . '/custom-fields/location.php');
	}
	
	/* From here down, pasted in from ACF auto-generator */
	
	/**
	 * Activate Add-ons
	 * Here you can enter your activation codes to unlock Add-ons to use in your theme. 
	 * Since all activation codes are multi-site licenses, you are allowed to include your key in premium themes. 
	 * Use the commented out code to update the database with your activation code. 
	 * You may place this code inside an IF statement that only runs on theme activation.
	 */ 
	// if(!get_option('acf_repeater_ac')) update_option('acf_repeater_ac', "xxxx-xxxx-xxxx-xxxx");
	// if(!get_option('acf_options_ac')) update_option('acf_options_ac', "xxxx-xxxx-xxxx-xxxx");
	// if(!get_option('acf_flexible_content_ac')) update_option('acf_flexible_content_ac', "xxxx-xxxx-xxxx-xxxx");
	
	
	/**
	 * Register field groups
	 * The register_field_group function accepts 1 array which holds the relevant data to register a field group
	 * You may edit the array as you see fit. However, this may result in errors if the array is not compatible with ACF
	 * This code must run every time the functions.php file is read
	 */
	if(function_exists("register_field_group"))
	{
	register_field_group(array (
	  'id' => '4fbbffe9f3ef2',
	  'title' => 'Pattern Details',
	  'fields' => 
	  array (
		0 => 
		array (
		  'key' => 'field_4fbac3f8613cc',
		  'label' => 'Context (uplinks)',
		  'name' => 'context',
		  'type' => 'wysiwyg',
		  'instructions' => 'What context does this pattern exist in?
	This is the place for links to larger patterns, and any cultural or climactic considerations.',
		  'required' => '1',
		  'toolbar' => 'full',
		  'media_upload' => 'yes',
		  'order_no' => '0',
		),
		1 => 
		array (
		  'key' => 'field_4fbac3f861742',
		  'label' => 'Problem / Conflict',
		  'name' => 'conflict',
		  'type' => 'wysiwyg',
		  'instructions' => 'What is the problem or conflict that this pattern identifies and speaks to?',
		  'required' => '1',
		  'toolbar' => 'full',
		  'media_upload' => 'yes',
		  'order_no' => '1',
		),
		2 => 
		array (
		  'key' => 'field_4fbac5d231348',
		  'label' => 'Resolution',
		  'name' => 'resolution',
		  'type' => 'wysiwyg',
		  'instructions' => 'How is the conflict resolved?
	(This is the "therefore...")',
		  'required' => '1',
		  'toolbar' => 'full',
		  'media_upload' => 'yes',
		  'order_no' => '2',
		),
		3 => 
		array (
		  'key' => 'field_4fbbc3a0aeb72',
		  'label' => 'Discussion',
		  'name' => 'discussion',
		  'type' => 'wysiwyg',
		  'instructions' => 'What other considerations apply here?  What research is relevant?  ',
		  'required' => '0',
		  'toolbar' => 'full',
		  'media_upload' => 'yes',
		  'order_no' => '3',
		),
		4 => 
		array (
		  'label' => 'Within this Pattern (downlinks)',
		  'name' => 'downlinks',
		  'type' => 'wysiwyg',
		  'instructions' => 'What patterns and dynamics should be considered within this pattern?  
	(This field may need a better name.)',
		  'required' => '0',
		  'toolbar' => 'full',
		  'media_upload' => 'yes',
		  'key' => 'field_4fbbd0ba62ff8',
		  'order_no' => '4',
		),
	  ),
	  'location' => 
	  array (
		'rules' => 
		array (
		  0 => 
		  array (
			'param' => 'post_type',
			'operator' => '==',
			'value' => 'pattern',
			'order_no' => '0',
		  ),
		),
		'allorany' => 'all',
	  ),
	  'options' => 
	  array (
		'position' => 'normal',
		'layout' => 'default',
		'show_on_page' => 
		array (
		  0 => 'the_content',
		  1 => 'custom_fields',
		  2 => 'discussion',
		  3 => 'comments',
		  4 => 'slug',
		  5 => 'author',
		),
	  ),
	  'menu_order' => 0,
	));
	}
	register_field_group(array (
	  'id' => '4fbbffea0faee',
	  'title' => 'Location',
	  'fields' => 
	  array (
		0 => 
		array (
		  'label' => 'Location',
		  'name' => 'location',
		  'type' => 'location',
		  'instructions' => '',
		  'required' => '0',
		  'key' => 'field_4fbbe6c391e88',
		  'order_no' => '0',
		),
	  ),
	  'location' => 
	  array (
		'rules' => 
		array (
		  0 => 
		  array (
			'param' => 'post_type',
			'operator' => '==',
			'value' => 'place',
			'order_no' => '0',
		  ),
		),
		'allorany' => 'all',
	  ),
	  'options' => 
	  array (
		'position' => 'side',
		'layout' => 'default',
		'show_on_page' => 
		array (
		  0 => 'the_content',
		  1 => 'custom_fields',
		  2 => 'discussion',
		  3 => 'comments',
		  4 => 'slug',
		  5 => 'author',
		),
	  ),
	  'menu_order' => 0,
	));

}

function add_widgets() {
	if ( function_exists('register_sidebar') ){
 	   //Filter sidebar - for index and archive pages
 	   register_sidebar(array(
        	'name' => 'my_filter_list',
        	'before_widget' => '<div id="filter-list">',
        	'after_widget' => '</div>',
        	'before_title' => '',
        	'after_title' => '',
		));
	}

}

function taxonomies_register() {
	
	register_taxonomy('scale','pattern',
		array(
			'label' => __('Scale'),
			'hierarchical' => true,
			'query_var' => true,
			'capabilities' => array(
				'manage_terms' => 'manage_categories',
			        'edit_terms' => 'manage_categories',
			        'delete_terms' => 'manage_categories',
			        'assign_terms' => 'edit_posts'
			)
		)
	);
	register_taxonomy_for_object_type('scale','place');
	register_taxonomy_for_object_type('post_tag', 'pattern');
}

function menus_register() {
   if ( function_exists( 'register_nav_menus' ) )
        register_nav_menus(array(
                'user' => __('Logged In'),
                'visitor' => __('Logged Out')
        ));
}

function my_get_posts( $query ) {
	if ( is_home() || is_archive() )
		$query->set( 'post_type', array( 'post', 'pattern', 'place') );

	return $query;
} 

function types_register() {
 
	$pat_labels = array(
		'name' => _x('Patterns', 'post type general name'),
		'singular_name' => _x('Pattern', 'post type singular name'),
		'add_new' => _x('Add New', 'pattern'),
		'add_new_item' => __('Add New Pattern'),
		'edit_item' => __('Edit Pattern'),
		'new_item' => __('New Pattern'),
		'view_item' => __('View Pattern'),
		'search_items' => __('Search Patterns'),
		'not_found' =>  __('No patterns found'),
		'not_found_in_trash' => __('No patterns found in Trash'),
		'parent_item_colon' => ''
	);
 
	$pat_args = array(
		'labels' => $pat_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','author','thumbnail','comments')
	  ); 
 
	register_post_type( 'pattern' , $pat_args );
	
	
	$place_labels = array(
		'name' => _x('Places', 'post type general name'),
		'singular_name' => _x('Place', 'post type singular name'),
		'add_new' => _x('Add New', 'place'),
		'add_new_item' => __('Add New Place'),
		'edit_item' => __('Edit Place'),
		'new_item' => __('New Place'),
		'view_item' => __('View Place'),
		'search_items' => __('Search Places'),
		'not_found' =>  __('No places found'),
		'not_found_in_trash' => __('No places found in Trash'),
		'parent_item_colon' => ''
	);
 
	$place_args = array(
		'labels' => $place_labels,
		'public' => true,
		'publicly_queryable' => true,
		'show_ui' => true,
		'query_var' => true,
		'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => null,
		'supports' => array('title','editor','author','thumbnail','comments')
	  ); 
 
	register_post_type( 'place' , $place_args );		
}

function connections_register() {

	if ( !function_exists( 'p2p_register_connection_type' ) )
		return;

	p2p_register_connection_type( array(
		'name' => 'places_to_patterns',
		'from' => 'place',
		'to' => 'pattern'
	) );
}

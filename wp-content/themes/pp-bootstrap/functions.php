<?php
add_action('init', 'types_register');
add_action( 'wp_loaded', 'connections_register' );
add_filter( 'pre_get_posts', 'my_get_posts' );
add_action('init', 'menus_register');
add_action('init', 'taxonomies_register');
add_action('wp_loaded', 'add_widgets');
add_action('after_setup_theme', 'fields_register');
add_filter( 'show_admin_bar', '__return_false' ); //Nuke the admin bar
add_action('login_head', 'my_login_css');
add_action('admin_menu', 'remove_menus', 102);


function remove_menus()
{
global $submenu;

	remove_menu_page( 'edit.php' ); // Posts
	//remove_menu_page( 'upload.php' ); // Media
	//remove_menu_page( 'link-manager.php' ); // Links
	//remove_menu_page( 'edit-comments.php' ); // Comments
	//remove_menu_page( 'edit.php?post_type=page' ); // Pages
	//remove_menu_page( 'plugins.php' ); // Plugins
	//remove_menu_page( 'themes.php' ); // Appearance
	//remove_menu_page( 'users.php' ); // Users
	//remove_menu_page( 'tools.php' ); // Tools
	//remove_menu_page(‘options-general.php’); // Settings
	
	//remove_submenu_page ( 'index.php', 'update-core.php' );    //Dashboard->Updates
	//remove_submenu_page ( 'themes.php', 'themes.php' ); // Appearance-->Themes
	//remove_submenu_page ( 'themes.php', 'widgets.php' ); // Appearance-->Widgets
	//remove_submenu_page ( 'themes.php', 'theme-editor.php' ); // Appearance-->Editor
	//remove_submenu_page ( 'options-general.php', 'options-general.php' ); // Settings->General
	//remove_submenu_page ( 'options-general.php', 'options-writing.php' ); // Settings->writing
	//remove_submenu_page ( 'options-general.php', 'options-reading.php' ); // Settings->Reading
	//remove_submenu_page ( 'options-general.php', 'options-discussion.php' ); // Settings->Discussion
	//remove_submenu_page ( 'options-general.php', 'options-media.php' ); // Settings->Media
	//remove_submenu_page ( 'options-general.php', 'options-privacy.php' ); // Settings->Privacy
}

function my_login_css() {
  echo '<link rel="stylesheet" type="text/css" href="' . get_stylesheet_directory_uri() . '/login-style.css' . '">';
}

function pp_thumbnail() {

	global $post;

	$id = (int) $post->ID;
	if ( $id == 0 ) {
		return false;
	}

	$html = get_the_post_thumbnail($id, array(150,150));
	if(!empty($html)){
		echo '<a href="' .get_permalink($id) .'">' .$html .'</a>';
	} 
	//Added for posts w/out thumbnail - EI 4 July 2012
	else {
		echo '<a href="' .get_permalink($id) .'"><img src="'. get_stylesheet_directory_uri() . '/images/NoPic.png"/></a>';
	}
}

function fields_register() {
	//Custom field for google map
	if (function_exists('register_field')) {
		register_field('Location_field', dirname(__FILE__) . '/custom-fields/location.php');
	}
}

function add_widgets() {
	if ( function_exists('register_sidebar') ){
 	   //Filter sidebar - for index and archive pages
 	   register_sidebar(array(
        	'name' => 'my_filter_list',
        	'before_widget' => '<div class="filter-list">',
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
	if ( is_home() || is_archive() ) {
		$query->set( 'post_type', array( 'nav_menu_item', 'pattern', 'place') );
	}
	if ( is_home() ) {	
		$query->set('orderby', 'meta_value');
		$query->set('meta_key', '_thumbnail_id');	
	}
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
		//'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 2,
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
		//'menu_icon' => get_stylesheet_directory_uri() . '/article16.png',
		'rewrite' => true,
		'capability_type' => 'post',
		'hierarchical' => false,
		'menu_position' => 3,
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

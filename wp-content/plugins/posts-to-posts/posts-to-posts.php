<?php
/*
Plugin Name: Posts 2 Posts
Description: Create many-to-many relationships between all types of posts.
Version: 1.3.1
Author: scribu
Author URI: http://scribu.net/
Plugin URI: http://scribu.net/wordpress/posts-to-posts
Text Domain: posts-to-posts
Domain Path: /lang
*/

define( 'P2P_PLUGIN_VERSION', '1.3.1' );

define( 'P2P_TEXTDOMAIN', 'posts-to-posts' );

require dirname( __FILE__ ) . '/scb/load.php';

function _p2p_init() {
	$base = dirname( __FILE__ );

	load_plugin_textdomain( P2P_TEXTDOMAIN, '', basename( $base ) . '/lang' );

	_p2p_load_files( "$base/core", array(
		'storage', 'query', 'query-post', 'query-user', 'url-query',
		'util', 'side', 'list', 'type-factory', 'type', 'directed-type',
		'api', 'extra'
	) );

	P2P_Widget::init();
	P2P_Shortcodes::init();

	if ( is_admin() ) {
		_p2p_load_files( "$base/admin", array(
			'mustache',
			'box-factory', 'box', 'fields',
			'column-factory', 'column',
			'tools'
		) );
	}

	register_uninstall_hook( __FILE__, array( 'P2P_Storage', 'uninstall' ) );
}
scb_init( '_p2p_init' );


function _p2p_load_files( $dir, $files ) {
	foreach ( $files as $file )
		require_once "$dir/$file.php";
}


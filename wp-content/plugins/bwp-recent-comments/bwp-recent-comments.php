<?php
/*
Plugin Name: Better WordPress Recent Comments
Plugin URI: http://betterwp.net/wordpress-plugins/bwp-recent-comments/
Description: This plugin displays recent comment lists at assigned locations. It does not add any significant load to your website. The comment list is updated on the fly when a visitor adds a comment or when you moderate one. No additional queries are needed for end-users. Some Icons by <a href="http://p.yusukekamiyamane.com/">Yusuke Kamiyamane</a>.
Version: 1.2.0
Text Domain: bwp-rc
Domain Path: /languages/
Author: Khang Minh
Author URI: http://betterwp.net
License: GPLv3
*/

// Frontend
require_once(dirname(__FILE__) . '/includes/class-bwp-recent-comments.php');
$bwp_rc = new BWP_RC();

// Backend
add_action('admin_menu', 'bwp_rc_init_admin', 1);

function bwp_rc_init_admin()
{
	global $bwp_rc;

	$bwp_rc->init_admin();
}
?>
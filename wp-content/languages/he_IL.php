<?php

$text_direction = 'rtl';

/* ------------------------------------------------------------------------- 
Localize defaults
http://trac.wordpress.org/ticket/6854
------------------------------------------------------------------------- */

function schema_il() {
	add_option('start_of_week', 0); 
	add_option('rss_language', 'he');
	add_option('timezone_string', 'Asia/Jerusalem');
}

add_action ('populate_options', 'schema_il');

/* ------------------------------------------------------------------------- 
Better wp-admin styles (No more Tahoma!)
------------------------------------------------------------------------- */

function wph_admin() {
	$url = content_url();
	$url = $url . '/languages/he_IL.css';
	echo '<link rel="stylesheet" type="text/css" href="' . $url . '" />';
}

add_action('admin_head', 'wph_admin');
add_action('login_head', 'wph_admin');

/* -------------------------------------------------------------------------
Unfancy Quote Plugin For WordPress by Semiologic
Slightly modified for better Hebrew support
http://www.semiologic.com/software/unfancy-quote/
------------------------------------------------------------------------- */

add_filter('category_description', 'strip_fancy_quotes', 20);
add_filter('list_cats', 'strip_fancy_quotes', 20);
add_filter('comment_author', 'strip_fancy_quotes', 20);
add_filter('comment_text', 'strip_fancy_quotes', 20);
add_filter('single_post_title', 'strip_fancy_quotes', 20);
add_filter('the_title', 'strip_fancy_quotes', 20);
add_filter('the_content', 'strip_fancy_quotes', 20);
add_filter('the_excerpt', 'strip_fancy_quotes', 20);
add_filter('bloginfo', 'strip_fancy_quotes', 20);
add_filter('widget_text', 'strip_fancy_quotes', 20);

/**
 * strip_fancy_quotes()
 *
 * @param string $text
 * @return string $text
 **/

function strip_fancy_quotes($text = '') {
	$text = str_replace(array("&#8216;", "&#8217;", "&#8242;"), "'", $text);
	$text = str_replace(array("&#8220;", "&#8221;", "&#8243;"), "\"", $text);

	return $text;
} # strip_fancy_quotes()

?>
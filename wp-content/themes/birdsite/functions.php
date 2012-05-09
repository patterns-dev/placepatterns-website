<?php

//////////////////////////////////////////
// Set the content width based on the theme's design and stylesheet.
if ( ! isset( $content_width ) )
	$content_width = 610;

//////////////////////////////////////////
// Set Widgets
function birdsite_widgets_init() {

	if ( function_exists('register_sidebar') ){
		register_sidebar( array (
			'name' => 'widget-area',
			'id' => 'widget-area',
			'description' => 'Widget Area for footer',
			'before_widget' => '<div class="widget">',
			'after_widget' => '</div>',
			'before_title' => '<h3>',
			'after_title' => '</h3>',
		) );
	}
}

//////////////////////////////////////////
// SinglePage Comment callback
function birdsite_custom_comments($comment, $args) {
	$GLOBALS['comment'] = $comment;

?>

	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

		<?php if('pingback' == $comment->comment_type || 'trackback' == $comment->comment_type):
			$url    = get_comment_author_url();
			$author = get_comment_author();
		 ?> 
			<div class="posted"><?php _e( 'Pingback', 'birdsite' ); ?></div>

			<div class="web"><a href="<?php echo $url; ?>" target="_blank"><?php echo $author ?></a><?php edit_comment_link( __('(Edit)', 'birdsite'), ' ' ); ?></div>

		<?php else: ?>
			<div class="posted">
				<div class="author"><?php comment_author(); ?></div>
				<div class="time"><?php echo get_comment_time(get_option('date_format') .' H:i'); ?></div>
				<?php echo get_avatar( $comment, 40 ); ?>
			</div>

			<?php comment_text(); ?>
			<?php $author_url = get_comment_author_url(); ?>
			<?php if(!empty($author_url)): ?>
				<div class="web"><a href="<?php echo $author_url; ?>" target="_blank"><?php echo $author_url; ?></a></div>
			<?php endif; ?>
		<?php endif; ?>
<?php
	// no "</li>" conform WORDPRESS
}

//////////////////////////////////////////////////////
// Pagenation
function birdsite_the_pagenation() {

	global $wp_rewrite;
	global $wp_query;
	global $paged;

	$paginate_base = get_pagenum_link(1);
	if (strpos($paginate_base, '?') || ! $wp_rewrite->using_permalinks()) {
		$paginate_format = '';
		$paginate_base = add_query_arg('paged', '%#%');
	} else {
		$paginate_format = (substr($paginate_base, -1 ,1) == '/' ? '' : '/') .
		user_trailingslashit('page/%#%/', 'paged');;
		$paginate_base .= '%_%';
	}
	echo paginate_links( array(
		'base' => $paginate_base,
		'format' => $paginate_format,
		'total' => $wp_query->max_num_pages,
		'mid_size' => 3,
		'current' => ($paged ? $paged : 1),
	));
}

//////////////////////////////////////////////////////
// Search form
function birdsite_search_form( $form ) {

	$search_string = '';
	if(is_search()){
		$search_string = get_search_query();
	}

	$form = '<form method="get" id="searchform" action="' .home_url( '/' ) .'">
			<div id="qsearch">
				<input type="text" name="s" id="s" value="' .$search_string .'" />
					<input class="btn" alt="' .__('Search', 'birdsite') .'" type="image" src="' .get_bloginfo('template_url') .'/images/search.gif" title="' .__('Search', 'birdsite') .'" id="searchsubmit" value="' . __('Search', 'birdsite') .'" />
			</div>
	    </form>';

    return $form;
}

//////////////////////////////////////////////////////
// Show thumbnail
function birdsite_the_thumbnail() {

	global $post;

	$id = (int) $post->ID;
	if ( $id == 0 ) {
		return false;
	}

	$html = get_the_post_thumbnail($id, array(150,150));
	if(!empty($html)){
		echo '<a href="' .get_permalink($id) .'">' .$html .'</a>';
	}
}

//////////////////////////////////////////////////////
// Header Style
function birdsite_header_style() {
?>
<style type="text/css">
#header {
   width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
   height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
   background: #FFF url(<?php header_image(); ?>) no-repeat center;
}

<?php
	if ( 'blank' == get_header_textcolor() ) { ?>
		#header * {
			display: none;
			}   
	<?php } else { ?>
		#header *,
		#header ul li a,
		#header ul li a:hover,
		#header ul li.current_page_item > a {
			color: #<?php header_textcolor();?>;
			}
		#header ul li {
			border-right-color: #<?php header_textcolor();?>;
			}
		a, a:visited {
			color: #<?php header_textcolor();?>;
			}

	<?php } ?>
</style>
<?php 
}

//////////////////////////////////////////////////////
// Admin Header Style
function birdsite_admin_header_style() {
?>
<style type="text/css">
#headimg {
   width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
   height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
   background: url(<?php header_image(); ?>) no-repeat center;   
}

#headimg *
{
   text-decoration: none;
   color: #<?php header_textcolor();?>;
}
</style>
<?php
} 

//////////////////////////////////////////////////////
// Setup Theme
function birdsite_setup() {

	// Set languages
	load_theme_textdomain( 'birdsite', TEMPLATEPATH . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Set feed
	add_theme_support( 'automatic-feed-links' );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'birdsite' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();

	// Add a way for the custom header
	define( 'HEADER_TEXTCOLOR', '06C' );
	define( 'HEADER_IMAGE', '%s/images/headers/buna.jpg' );
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 610 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 200 ) );
	add_custom_image_header( 'birdsite_header_style', 'birdsite_admin_header_style' );

	register_default_headers( array(
		'buna' => array(
			'url' => '%s/images/headers/buna.jpg',
			'thumbnail_url' => '%s/images/headers/buna-thumbnail.jpg',
			'description' => 'Buna'
		),
		'keyaki' => array(
			'url' => '%s/images/headers/keyaki.jpg',
			'thumbnail_url' => '%s/images/headers/keyaki-thumbnail.jpg',
			'description' => 'Keyaki'
		),
		'hinoki' => array(
			'url' => '%s/images/headers/hinoki.jpg',
			'thumbnail_url' => '%s/images/headers/hinoki-thumbnail.jpg',
			'description' => 'Hinoki'
		),
		'sugi' => array(
			'url' => '%s/images/headers/sugi.jpg',
			'thumbnail_url' => '%s/images/headers/sugi-thumbnail.jpg',
			'description' => 'Sugi'
		),
		'kusu' => array(
			'url' => '%s/images/headers/kusu.jpg',
			'thumbnail_url' => '%s/images/headers/kusu-thumbnail.jpg',
			'description' => 'Kusu'
		),
		'white' => array(
			'url' => '%s/images/headers/white.jpg',
			'thumbnail_url' => '%s/images/headers/white-thumbnail.jpg',
			'description' => 'White'
		)
	) );
}

//////////////////////////////////////////////////////
// Action Hook
add_action( 'widgets_init', 'birdsite_widgets_init' );
add_action( 'after_setup_theme', 'birdsite_setup' );  
add_filter( 'get_search_form', 'birdsite_search_form' );
?>
<?php get_header(); ?>
			
			
<div id="content" class="clearfix row">
	<div id="left-sidebar" class="span3">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('my_filter_list') ) : ?>
		<?php endif; ?>
	</div>
			
	<div id="main" class="span9 clearfix" role="main">
	
		<div class="page-header">
			<?php if (is_category()) { ?>
				<h1 class="archive_title h2">
					<?php single_cat_title(); ?>
				</h1>
			<?php } elseif (is_tag()) { ?> 
				<h1 class="archive_title h2">
					<?php single_tag_title(); ?>
				</h1>
			<?php } elseif (is_tax()) { ?> 
				<h1 class="archive_title h2">
					<span> <?php single_tag_title(); ?> Patterns and Places</span> 
				</h1>
			<?php } elseif (is_author()) { 
				//This ain't workin!
				$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
			?>
				<h1 class="archive_title h2">
					<span><?php _e("Patterns and Places By:", "bonestheme"); ?></span> <?php echo get_the_author_meta('display_name', $curauth->ID); ?>
				</h1>
			<?php } elseif (is_day()) { ?>
				<h1 class="archive_title h2">
					<span><?php _e("Daily Archives:", "bonestheme"); ?></span> <?php the_time('l, F j, Y'); ?>
				</h1>
			<?php } elseif (is_month()) { ?>
				<h1 class="archive_title h2">
					<span><?php _e("Monthly Archives:", "bonestheme"); ?>:</span> <?php the_time('F Y'); ?>
				</h1>
			<?php } elseif (is_year()) { ?>
				<h1 class="archive_title h2">
					<span><?php _e("Yearly Archives:", "bonestheme"); ?>:</span> <?php the_time('Y'); ?>
				</h1>
			<?php } ?>
		</div>

		<div id="thumbnail"> <ul>
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					<?php pp_thumbnail(); ?>
				</li>
			<?php endwhile; ?>	
		
		</ul> </div>
		
		<?php if (function_exists('page_navi')) { // if expirimental feature is active ?>
			
			<?php page_navi(); // use the page navi function ?>

		<?php } else { // if it is disabled, display regular wp prev & next links ?>
			<nav class="wp-prev-next">
				<ul class="clearfix">
					<li class="prev-link"><?php next_posts_link(_e('&laquo; Older Entries', "bonestheme")) ?></li>
					<li class="next-link"><?php previous_posts_link(_e('Newer Entries &raquo;', "bonestheme")) ?></li>
				</ul>
			</nav>
		<?php } ?>
					
		
		<?php else : ?>
		
		<article id="post-not-found">
			<header>
				<h1><?php _e("No Posts Yet", "bonestheme"); ?></h1>
			</header>
			<section class="post_content">
				<p><?php _e("Sorry, What you were looking for is not here.", "bonestheme"); ?></p>
			</section>
			<footer>
			</footer>
		</article>
		
		<?php endif; ?>

	</div> <!-- end #main -->

	<?php // get_sidebar(); // sidebar 1 ?>

</div> <!-- end #content -->

<?php get_footer(); ?>
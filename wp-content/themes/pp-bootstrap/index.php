<?php get_header(); ?>
			
<div id="content" class="clearfix row">
	<div id="left-sidebar" class="span3">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('my_filter_list') ) : ?>
		<?php endif; ?>
	</div>
	
	
	<div id="main" class="span9 clearfix" role="main">
		<div id="thumbnail"> <ul>
			<?php if (have_posts()) : while (have_posts()) : the_post(); 
			    if (get_post_type()=='nav_menu_item') continue; ?>
				
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
				<h1><?php _e("Not Found", "bonestheme"); ?></h1>
			</header>
			<section class="post_content">
				<p><?php _e("Sorry, but the requested resource was not found on this site.", "bonestheme"); ?></p>
			</section>
			<footer>
			</footer>
		</article>
		
		<?php endif; ?>

	</div> <!-- end #main -->

	<?php // get_sidebar(); // sidebar 1 ?>
	
</div> <!-- end #content -->

<?php get_footer(); ?>


<?php if(false): ?>

		<div id="left-sidebar">
		<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('my_filter_list') ) : ?>
		<?php endif; ?>
		</div>
		
		<div id="content">
			<?php if (have_posts()) : ?>
				<div id="thumbnail">
				<ul>
					<?php while (have_posts()) : the_post(); ?>
					<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php pp_thumbnail(); ?>
					<h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2>
					</li><?php endwhile; // no CR conform CSS ?> 
				</ul>
				</div>
		
			<div class="tablenav"><?php birdsite_the_pagenation(); ?></div>
		
			<?php endif; ?>
		
		</div>
		
		<?php // get_sidebar(); ?>
		<?php get_footer(); ?>

<?php endif; ?>
<?php get_header(); ?>

<div id="content">

	<h1 class="entry-title"><?php _e('Error 404 - Not Found', 'birdsite'); ?></h1>
	<h2><?php _e('Recent Articles', 'birdsite'); ?></h2>

   <?php query_posts('cat=&showposts=5'); ?>
   <ul>
		<?php while (have_posts()) : the_post(); ?>
			<li>
				<a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a> 
        		</li>
   		<?php endwhile; ?>
    </ul>
</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

<?php get_header(); ?>

<div id="content">
	<?php if (have_posts()) : ?>
		<div id="thumbnail">
		<ul>
			<?php while (have_posts()) : the_post(); ?><li id="post-<?php the_ID(); ?>" <?php post_class(); ?>><?php birdsite_the_thumbnail(); ?><h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2><p><?php the_time(get_option('date_format')); ?>
			<?php if ('closed' <> $post->comment_status) : ?><br /><?php comments_popup_link(__('No Comments &#187;', 'birdsite'), __('1 Comment &#187;', 'birdsite'), __('% Comments &#187;', 'birdsite'), '', __('Comments Closed', 'birdsite') ); ?>
				<?php endif; ?>
			</p></li><?php endwhile; // no CR conform CSS ?> 
		</ul>
		</div>

	<div class="tablenav"><?php birdsite_the_pagenation(); ?></div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

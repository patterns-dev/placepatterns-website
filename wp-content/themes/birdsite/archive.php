<?php get_header(); ?>

<div id="content">
  
	<?php if (have_posts()) : ?>

	 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
	 	  <?php /* If this is a category archive */ if (is_category()) { ?>
			<h1 class="pagetitle"><?php printf(__('Archive for the &#8216;%s&#8217; Category', 'birdsite'), single_cat_title('', false)); ?></h1>
	 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
			<h1 class="pagetitle"><?php printf(__('Posts Tagged &#8216;%s&#8217;', 'birdsite'), single_tag_title('', false) ); ?></h1>
	 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
			<h1 class="pagetitle"><?php printf(__('Archive for %s|Daily archive page', 'birdsite'), get_the_time(__('F jS, Y', 'birdsite'))); ?></h1>
	 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
			<h1 class="pagetitle"><?php printf(__('Archive for %s|Monthly archive page', 'birdsite'), get_the_time(__('F, Y', 'birdsite'))); ?></h1>
	 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
			<h1 class="pagetitle"><?php printf(__('Archive for %s|Yearly archive page', 'birdsite'), get_the_time(__('Y', 'birdsite'))); ?></h1>
		  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
			<h1 class="pagetitle"><?php _e('Author Archive', 'birdsite'); ?></h1>
	 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
			<h1 class="pagetitle"><?php _e('Blog Archives', 'birdsite'); ?></h1>
	 	  <?php } ?>

		<div id="thumbnail">
		<ul>
			<?php while (have_posts()) : the_post(); ?><li id="post-<?php the_ID(); ?>" <?php post_class(); ?>><?php birdsite_the_thumbnail(); ?><h2><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h2><p><?php the_time(get_option('date_format')); ?>
			<?php if ('closed' <> $post->comment_status) : ?><br /><?php comments_popup_link(__('No Comments &#187;', 'birdsite'), __('1 Comment &#187;', 'birdsite'), __('% Comments &#187;', 'birdsite'), '', __('Comments Closed', 'birdsite') ); ?>
				<?php endif; ?>
			</p></li><?php endwhile; // no CR conform CSS ?> 
		</ul>
		</div>
		
		<div class="tablenav"><?php birdsite_the_pagenation(); ?>	</div>

	<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

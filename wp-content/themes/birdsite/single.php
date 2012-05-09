<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="post">

		<h1><?php the_title(); ?></h1>

		<?php the_content(); ?>

		<?php wp_link_pages( array( 'before' => '<div class="page-link">' . __( 'Pages:', 'birdsite' ), 'after' => '</div>' ) ); ?>

		<div class="posttag">
			<div><?php the_date(get_option('date_format')); ?> <?php the_author(); ?> <?php edit_post_link( __( 'Edit', 'birdsite' ), ' ' ); ?></div>
			<?php echo __('Posted in:', 'birdsite'); the_category(' , ') ?>
			<?php the_tags('<br />' .__('Tags', 'birdsite') .': ', ' , ', ''); ?>
		</div>

	</div>

	<?php comments_template(); ?>

	<?php endwhile; else: ?>

		<p><?php printf(__('Sorry, no posts matched your criteria.', 'birdsite'), wp_specialchars($s) ); ?></p>

<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

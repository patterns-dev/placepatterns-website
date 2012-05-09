<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

    <div class="post" id="post-<?php the_ID(); ?>">
        <h1><?php the_title(); ?></h1>
		<?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
	</div>
	<?php comments_template( '', true ); ?>

	<?php endwhile; endif; ?>

</div>


<?php get_sidebar(); ?>
<?php get_footer(); ?>

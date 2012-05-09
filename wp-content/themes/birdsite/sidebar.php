<div id="sidebar">

<?php
	if(!dynamic_sidebar('widget-area')): ?>

		<ul class="widget">
			<?php wp_list_categories('show_count=1&title_li=<h3>' . __('Categories', 'birdsite') . '</h3>'); ?>
		</ul>

		<div class="widget">
			<h3><?php _e('Archives', 'birdsite'); ?></h3>
			<ul>
				<?php wp_get_archives( 'type=monthly' ); ?>
			</ul>
		</div>

		<div class="widget">
			<h3><?php _e('Meta', 'birdsite'); ?></h3>
			<ul>
				<?php wp_register(); ?>
				<li><?php wp_loginout(); ?></li>
				<?php wp_meta(); ?>
			</ul>
		</div>

		<?php get_search_form(); ?>

	<?php endif; ?>
</div>


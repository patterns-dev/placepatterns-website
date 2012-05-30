<?php get_header(); ?>

<div id="content">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	<div class="post">

		<?php if ( has_post_thumbnail() ): // check if the post has a Post Thumbnail assigned to it.
		  	the_post_thumbnail('medium');
		endif; ?>
		
		<h1><?php the_title(); ?></h1>

		<?php the_content(); ?>

				<?php // Find connected pages
				$connected = new WP_Query( array('connected_type' => 'places_to_patterns','connected_items' => get_queried_object(),'nopaging' => true) );
				
				// Display connected pages
				if ( $connected->have_posts() ) : ?>
				
				<h3>Patterns in this place:</h3>
				<div id="thumbnail">
				<ul>
				<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
				<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
					<?php birdsite_the_thumbnail(); ?>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
				<?php endwhile; ?>
				</ul>
				</div>
				
				<?php // Prevent weirdness 
				wp_reset_postdata(); endif; ?>

	</div>

	<?php comments_template(); ?>

	<div id="right-sidebar">
	
		<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description ?>
		<div id="author-info">
			<div id="author-avatar">
				<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyeleven_author_bio_avatar_size', 68 ) ); ?>
			</div><!-- #author-avatar -->
			<div id="author-description">
				<h2>From: <?php echo get_the_author(); ?></h2>
				<?php the_author_meta( 'description' ); ?>
				<div id="author-link">
					<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
						<?php printf( __( 'View all work from %s <span class="meta-nav">&rarr;</span>', 'twentyeleven' ), get_the_author() ); ?>
					</a>
				</div><!-- #author-link	-->
			</div><!-- #author-description -->
		</div><!-- #entry-author-info -->
		<?php endif; ?>
		
		<?php if(get_field('location')): ?>
			<div id="map" style="width: 220px; height: 154px; margin: 15px 0;"></div>
	
			<?php
				$location = get_field('location');
				$temp = explode(';', $location);
				$lat = (float) $temp[0];
				$lng = (float) $temp[1];
			?>
			<script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
			<script type="text/javascript">
			//<![CDATA[
			function loadMap() {
				var lat = <?php echo $lat; ?>;
				var lng = <?php echo $lng; ?>;
				// coordinates to latLng
				var latlng = new google.maps.LatLng(lat, lng);
				// map Options
				var myOptions = {
					zoom: 12,
					center: latlng,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				//draw a map
				var map = new google.maps.Map(document.getElementById("map"), myOptions);
				var marker = new google.maps.Marker({
					position: map.getCenter(),
					map: map
				});
			}
			// call the function
			loadMap();
			//]]>
			</script>
		
		<?php endif; ?>
		

		<?php the_tags('<br /> Tags: ', ', ', ''); ?>
		<?php the_terms(0, 'scale', '<br /> Scale: ', ', ', ''); ?>
	</div>

	<?php endwhile; else: ?>

		<p><?php printf(__('Sorry, no posts matched your criteria.', 'birdsite'), wp_specialchars($s) ); ?></p>

<?php endif; ?>

</div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>

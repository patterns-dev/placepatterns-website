<?php get_header(); ?>

<div id="content" class="clearfix row">
	<div id="main" class="clearfix" role="main">
		<div class="row">
			<div class="span8 offset2">
			<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

				<div class="post">

					<h1><?php the_title(); ?></h1>
		
					<?php if ( has_post_thumbnail() ): ?>
						<div id="lead-image"> <?php the_post_thumbnail('medium'); ?></div>
					<?php endif; ?>

					<span><?php the_terms(0, 'scale', '<br /> Scale: ', ', ', ''); ?></span>
					<span><?php the_tags('<br /> Tags: ', ', ', ''); ?></span>
								
					<?php the_content(); ?>
			</div>
		</div>

		<div id="author" class="row">
			<div class="authorbox span7 offset3">
				<div class="row">
									
					<div class="span6">
						<h3><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author">
								 <?php echo get_the_author(); ?>
							</a>
						</h3>
						<?php if ( get_the_author_meta( 'description' ) ) : // If a user has filled out their description ?>
							<div id="author-description">
								<?php
								$desc=nl2br (get_the_author_meta('description'));
								echo $desc;
								
								//the_author_meta( 'description' ); ?>
							</div><!-- #author-description -->
						<?php endif; ?>
			
					</div>
					<div class="span1">
						<div id="author-avatar">
							<?php echo get_avatar( get_the_author_meta( 'user_email' ), apply_filters( 'twentyeleven_author_bio_avatar_size', 68 ) ); ?>
						</div><!-- #author-avatar -->
					</div>
				</div>
			</div> <!--authorbox-->		
		</div>

		<div id="maprow" class="row">
			<div class="span3 offset2">

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
				
			</div>
		</div>
			
		<?php
		// Find connected pages
		$connected = new WP_Query( array('connected_type' => 'places_to_patterns','connected_items' => get_queried_object(),'nopaging' => true) );
		
		// Display connected pages
		if ( $connected->have_posts() ) : ?>
			<div id="related-places" class="row">
				<div class="span8 offset2">										
					<h3>Patterns in this Place:</h3>
					<div id="thumbnail">
						<ul>
						<?php while ( $connected->have_posts() ) : $connected->the_post(); ?>
						<li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
							<?php pp_thumbnail(); ?>
							<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
						</li>
						<?php endwhile; ?>
						</ul>
					</div>
					
					<?php // Prevent weirdness 
					wp_reset_postdata(); ?>				
				</div>
			</div>
		<?php endif; ?>	
	
	</div>


	<div id="comments" class="row">
		<div class="span8 offset2">
			<?php comments_template(); ?>
		</div>
	</div>
	


	<?php endwhile; else: ?>

		<p><?php printf(__('Sorry, no posts matched your criteria.', 'birdsite'), wp_specialchars($s) ); ?></p>

<?php endif; ?>

				</div> <!-- end #main -->
    
				<?php // get_sidebar(); // sidebar 1 ?>
    
			</div> <!-- end #content -->

<?php get_footer(); ?>

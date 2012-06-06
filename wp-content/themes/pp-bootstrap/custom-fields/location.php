<?php

/*
 *	Advanced Custom Fields - Google maps marker field
 *	
 *
 * 	@author Elliot Condon, Changes made by Evalds Urtans (www.asketic.com)
 */
 
 
class Location_field extends acf_Field
{

	/*--------------------------------------------------------------------------------------
	*
	*	Constructor
	*	- This function is called when the field class is initalized on each page.
	*	- Here you can add filters / actions and setup any other functionality for your field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function __construct($parent)
	{
		// do not delete!
    	parent::__construct($parent);
    	
    	// set name / title
    	$this->name = 'location'; // variable name (no spaces / special characters / etc)
		$this->title = __("Location",'acf'); // field label (Displayed in edit screens)
		
   	}

	function validate_options(&$field)
	{
		$field['api_key'] = isset($field['api_key']) ? $field['api_key'] : '';
		$field['map_center'] = isset($field['map_center']) ? $field['map_center'] : '56.95458774719434;24.104830026626587';
		$field['map_zoom'] = isset($field['map_zoom']) ? $field['map_zoom'] : '8';
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_options
	*	- this function is called from core/field_meta_box.php to create extra options
	*	for your field
	*
	*	@params
	*	- $key (int) - the $_POST obejct key required to save the options to the field
	*	- $field (array) - the field object
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_options($key, $field)
	{
		$this->validate_options($field);
		
		?>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('API key','acf'); ?></label>
				<p class="description"><?php _e("To have google map search by address function please get API Key from google API console",'acf'); ?></p></td>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'text',
					'name'	=>	'fields['.$key.'][api_key]',
					'value'	=>	$field['api_key']					
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Map center','acf'); ?></label>
				<p class="description"><?php _e("Example: 56.95458774719434;24.104830026626587",'acf'); ?></p></td>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'text',
					'name'	=>	'fields['.$key.'][map_center]',
					'value'	=>	$field['map_center']					
				));
				?>
			</td>
		</tr>
		<tr class="field_option field_option_<?php echo $this->name; ?>">
			<td class="label">
				<label><?php _e('Map zoom','acf'); ?></label>
				<p class="description"><?php _e("Example: 8",'acf'); ?></p></td>
			</td>
			<td>
				<?php 
				$this->parent->create_field(array(
					'type'	=>	'text',
					'name'	=>	'fields['.$key.'][map_zoom]',
					'value'	=>	$field['map_zoom']
				));
				?>
			</td>
		</tr>
		<?php
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	pre_save_field
	*	- this function is called when saving your acf object. Here you can manipulate the
	*	field object and it's options before it gets saved to the database.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function pre_save_field($field)
	{
		// do stuff with field (mostly format options data)
		
		return parent::pre_save_field($field);
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	create_field
	*	- this function is called on edit screens to produce the html for this field
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function create_field($field)
	{
		$this->validate_options($field);
		
		?>
		<style type="text/css">
			#map 
			{
				width: 100%; height: 500px; margin-top: 10px;
			}
			#map-find .imgedit-wait
			{
				background-position: 60px 10px;
			}
		</style>
	    <script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
	    
	    <?php
	    $mapCenter = explode(';', $field['map_center']);
	    
	    $hasApiKey = false;
	    if(strlen($field['api_key'])):
			$hasApiKey = true;
	    ?>
	    	<script src="http://www.google.com/uds/api?file=uds.js&amp;v=1.0&amp;key=<?= $field['api_key']; ?>" type="text/javascript"></script>
		<?php
		endif;
		?>
	    
		<script type="text/javascript">
			function gMapLoad() {
				var exists = 0, 
					marker, 
					zoom = 8;
					
				var maxZoomService = null;
				
				<?php
				if($hasApiKey):
				?>
					maxZoomService = new google.maps.MaxZoomService();
				<?php
				endif;
				?>
				
				// get the lat and lng from our input field
				var coords = jQuery('.location').val();
				// if input field is empty, default coords
				if (coords === '') {
					lat = <?= $mapCenter[0]; ?>; 
					lng = <?= $mapCenter[1]; ?>;
					zoom = <?= $field['map_zoom']; ?>;
				} else {
					// split the coords by ;
					temp = coords.split(';');
					lat = parseFloat(temp[0]);
					lng = parseFloat(temp[1]);					
					exists = 1;
				}
				// coordinates to latLng
				var latlng = new google.maps.LatLng(lat, lng);
				// map Options
				var myOptions = {
				  zoom: zoom,
				  center: latlng,
				  mapTypeId: google.maps.MapTypeId.ROADMAP
				};
				//draw a map
				var map = new google.maps.Map(document.getElementById("map"), myOptions);
				
				if(exists && maxZoomService)
				{
					maxZoomService.getMaxZoomAtLatLng(latlng, function(event){
						map.setZoom(event.zoom);	
					});				
				}
				
				// if we had coords in input field, put a marker on that spot
				if(exists == 1) {
					marker = new google.maps.Marker({
						position: map.getCenter(),
						map: map,
						scrollwheel: false,
						draggable: true
					});
				}
				
				// click event
				google.maps.event.addListener(map, 'click', function(point) {
					if (exists == 0) {
						exists = 1;
						// drawing the marker on the clicked spot
						marker = new google.maps.Marker({
							position: point.latLng,
							map: map,
							draggable: true
						});							
					} else {
						// only one marker on the map!
						//alert('Marker already on the map! Drag it to desired location.');
						marker.setPosition(point.latLng);
					}
					
					//put the coordinates to input field
					jQuery('.location').val(marker.getPosition().lat() + ';' + marker.getPosition().lng());
					// drag event for add screen
					google.maps.event.addListener(marker, "dragend", function (mEvent) { 
						jQuery('.location').val(mEvent.latLng.lat() + ';' + mEvent.latLng.lng());
					});
				});
				//dragend event for update screen
				if(exists === 1) {
					google.maps.event.addListener(marker, "dragend", function (mEvent) { 
						jQuery('.location').val(mEvent.latLng.lat() + ';' + mEvent.latLng.lng());
					});
				}
				
									
				
				jQuery('#map-location').change(function() {
						var input = $(this);
						if(input.val().indexOf(';'))
						{
							var arrPos = input.val().split(';');
							var ll = new google.maps.LatLng(parseFloat(arrPos[0]),
				                                            parseFloat(arrPos[1]));
							map.setCenter(ll);
							marker.setPosition(ll);	
							
							if(maxZoomService)
							{
								//Set max zoom										
								maxZoomService.getMaxZoomAtLatLng(ll, function(event){
									map.setZoom(event.zoom);	
								});
							}												 								
						}
				});
				
				if(maxZoomService)
				{
					//Address search
					//Initialize the local searcher
				    var gLocalSearch = new GlocalSearch();			
				    var gLoader = jQuery('#map-find .imgedit-wait');	    
				    
				    function OnLocalSearch() {
				    	  gLoader.hide();
					      if (!gLocalSearch.results.length) return;
					      
					      // Move the map to the first result
					      var first = gLocalSearch.results[0];					      
					      map.setCenter(new google.maps.LatLng(parseFloat(first.lat),
					                                            parseFloat(first.lng)));
					      jQuery('#map-location').val(map.getCenter().lat() + ';' + map.getCenter().lng()).trigger('change');
					      
				    }
				    gLocalSearch.setSearchCompleteCallback(null, OnLocalSearch);
	
					
					jQuery('#map-find-button').click(function(){
						var button = $(this);
						gLoader.show();
						
						gLocalSearch.setCenterPoint(map.getCenter());
	  					gLocalSearch.execute(jQuery('#map-find-value').val());      					
					});
				}
				
			}

		jQuery(document).ready(function(){
			if (jQuery('.location').length > 0) {
				gMapLoad();
			}
		});
		</script>
		<?php
		if($hasApiKey):
		?>
			<div id="map-find">
				<div>
					<label>Address search:</label>
				</div>
				<input type="text" id="map-find-value" value="" style="width: 350px;"/>
	          	<input type="button" id="map-find-button" value="Search"/>
	          	<div class="imgedit-wait"></div>
			</div>
		<?php
		endif;
		?>		
		<div>
			<label>Coordinates:</label>
		</div>
		<?php
			echo '<input id="map-location" type="text" style="width: 350px;" value="' . $field['value'] . '" id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" />';
		?>
		<div id="map"></div>
		<div>
			* Use drag&drop on pin to make corrections
		</div>
		<?php		
	}
	

	/*--------------------------------------------------------------------------------------
	*
	*	admin_print_scripts / admin_print_styles
	*	- this function is called in the admin_print_scripts / admin_print_styles where 
	*	your field is created. Use this function to register css and javascript to assist 
	*	your create_field() function.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_print_scripts()
	{
	
	}
	
	function admin_print_styles()
	{
		
	}

	
	/*--------------------------------------------------------------------------------------
	*
	*	update_value
	*	- this function is called when saving a post object that your field is assigned to.
	*	the function will pass through the 3 parameters for you to use.
	*
	*	@params
	*	- $post_id (int) - usefull if you need to save extra data or manipulate the current
	*	post object
	*	- $field (array) - usefull if you need to manipulate the $value based on a field option
	*	- $value (mixed) - the new value of your field.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function update_value($post_id, $field, $value)
	{
		// do stuff with value
		
		// save value
		parent::update_value($post_id, $field, $value);
	}
	
	
	
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value
	*	- called from the edit page to get the value of your field. This function is useful
	*	if your field needs to collect extra data for your create_field() function.
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value($post_id, $field)
	{
		// get value
		$value = parent::get_value($post_id, $field);
		
		// format value
		
		// return value
		return $value;		
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	get_value_for_api
	*	- called from your template file when using the API functions (get_field, etc). 
	*	This function is useful if your field needs to format the returned value
	*
	*	@params
	*	- $post_id (int) - the post ID which your value is attached to
	*	- $field (array) - the field object.
	*
	*	@author Elliot Condon
	*	@since 3.0.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function get_value_for_api($post_id, $field)
	{
		// get value
		$value = $this->get_value($post_id, $field);
		
		// format value
		
		// return value
		return $value;

	}
	
}

?>
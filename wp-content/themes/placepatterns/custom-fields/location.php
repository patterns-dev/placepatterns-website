<?php

/*
 *	Advanced Custom Fields - New field template
 *	
 *	Create your field's functionality below and use the function:
 *	register_field($class_name, $file_path) to include the field
 *	in the acf plugin.
 *
 *	Documentation: 
 *
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
		echo '<input type="text" value="' . $field['value'] . '" id="' . $field['name'] . '" class="' . $field['class'] . '" name="' . $field['name'] . '" />';
		echo '<div id="map"></div>';
	}
	
	
	/*--------------------------------------------------------------------------------------
	*
	*	admin_head
	*	- this function is called in the admin_head of the edit screen where your field
	*	is created. Use this function to create css and javascript to assist your 
	*	create_field() function.
	*
	*	@author Elliot Condon
	*	@since 2.2.0
	* 
	*-------------------------------------------------------------------------------------*/
	
	function admin_head()
	{
		?>
		<style type="text/css">
			#map {width: 100%; height: 500px; margin-top: 10px;}
		</style>
		    <script src='http://maps.googleapis.com/maps/api/js?sensor=false' type='text/javascript'></script>
			<script type="text/javascript">
				function load() {
					var exists = 0, marker;
					// get the lat and lng from our input field
					var coords = jQuery('.location').val();
					// if input field is empty, default coords
					if (coords === '') {
						lat = 45.77598686952638; 
						lng = 15.985933542251587;
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
					  zoom: 8,
					  center: latlng,
					  mapTypeId: google.maps.MapTypeId.ROADMAP
					};
					//draw a map
					var map = new google.maps.Map(document.getElementById("map"), myOptions);
					
					// if we had coords in input field, put a marker on that spot
					if(exists == 1) {
						marker = new google.maps.Marker({
							position: map.getCenter(),
							map: map,
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
							//put the coordinates to input field
							jQuery('.location').val(marker.getPosition().lat() + ';' + marker.getPosition().lng());
							// drag event for add screen
							google.maps.event.addListener(marker, "dragend", function (mEvent) { 
								jQuery('.location').val(mEvent.latLng.lat() + ';' + mEvent.latLng.lng());
							});
						} else {
							// only one marker on the map!
							alert('Marker already on the map! Drag it to desired location.');
						}
					});
					//dragend event for update screen
					if(exists === 1) {
						google.maps.event.addListener(marker, "dragend", function (mEvent) { 
							jQuery('.location').val(mEvent.latLng.lat() + ';' + mEvent.latLng.lng());
						});
					}
				}

			jQuery(document).ready(function(){
				if (jQuery('.location').length > 0) {
					load();
				}
			});
			</script>

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
<?php

/*
*  Gallery Field
*
*  @description: 
*  @created: 28/06/12
*/

class acf_Gallery extends acf_Field
{

	/*
	*  Constructer
	*
	*  @description: 
	*  @created: 5/07/12
	*/
	
	function __construct($parent)
	{
    	parent::__construct($parent);
    	
    	$this->name = 'gallery';
		$this->title = __("Gallery",'acf');
		
		
		// actions
		add_action('admin_head-media-upload-popup', array($this, 'popup_head'));
		add_action('acf_head-update_attachment-gallery', array($this, 'acf_head_update_attachment'));
		add_action('wp_ajax_acf_get_gallery_list_data', array($this, 'acf_get_gallery_list_data'));
   	}

	
	/*
	*  acf_get_gallery_list_data
	*
	*  @description: AJAX call to get updated list data after an image is edited
	*  @created: 5/07/12
	*/
	
   	function acf_get_gallery_list_data()
   	{
   		// vars
		$defaults = array(
			'attachment_id'	=>	false,
		);
		
		$options = array_merge($defaults, $_GET);
		
		
		// get attachment object
		$attachment = get_post( $options['attachment_id'] );
		
		
		// get fields
		$alt = get_post_meta($attachment->ID, '_wp_attachment_image_alt', true);
		$image_title = $attachment->post_title;
		$caption = $attachment->post_excerpt;
		$description = $attachment->post_content;
		
		?>
<table>
	<tbody>
		<tr>
			<th><label><?php _e("Title",'acf'); ?>:</label></th>
			<td><?php echo $image_title; ?></td>
		</tr>
		<tr>
			<th><label><?php _e("Alternate Text",'acf'); ?>:</label></th>
			<td><?php echo $alt; ?></td>
		</tr>
		<tr>
			<th><label><?php _e("Caption",'acf'); ?>:</label></th>
			<td><?php echo $caption; ?></td>
		</tr>
		<tr>
			<th><label><?php _e("Description",'acf'); ?>:</label></th>
			<td><?php echo $description; ?></td>
		</tr>
	</tbody>
</table>
		<?php
		
		// end ajax
		die();
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
		// vars
		$defaults = array(
			'preview_size'	=>	'thumbnail',
		);
		
		$field = array_merge($defaults, $field);
		
?>
<tr class="field_option field_option_<?php echo $this->name; ?>">
	<td class="label">
		<label><?php _e("Preview Size",'acf'); ?></label>
		<p class="description"><?php _e("Thumbnail is advised",'acf'); ?></p>
	</td>
	<td>
		<?php
		
		$image_sizes = $this->parent->get_all_image_sizes();
		
		$this->parent->create_field(array(
			'type'		=>	'radio',
			'name'		=>	'fields['.$key.'][preview_size]',
			'value'		=>	$field['preview_size'],
			'layout'	=>	'horizontal',
			'choices'	=>	$image_sizes
		));
		
		?>
	</td>
</tr>
<?php
		
	}

	
	function acf_head_update_attachment()
	{
		?>
<script type="text/javascript">
(function($){
	
	// vars
	var div = self.parent.acf_edit_attachment,
		attachment_id = div.attr('data-id');
	
	
	// ajax find new list data
	$.ajax({
		url: ajaxurl,
		data : {
			'action' : 'acf_get_gallery_list_data',
			'attachment_id' : attachment_id
		},
		cache: false,
		dataType: "html",
		success: function( html ) {
	    	

			// validate
			if(!html)
			{
				return false;
			}
			
			
			// update list-item html
			div.find('.list-data').html( html ); 	
 	
		}
	});
	
	
	// add message
	self.parent.acf.add_message('<?php _e("Image Updated",'acf'); ?>.', div);
	
		
})(jQuery);
</script>
		<?php
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
		// vars
		$defaults = array(
			'value'	=>	false,
			'preview_size' => 'thumbnail',
		);
		
		$field = array_merge($defaults, $field);
		
		?>
<div class="acf-gallery" data-preview_size="<?php echo $field['preview_size']; ?>">
	
	<input type="hidden" name="<?php echo $field['name']; ?>" value="" />
	
	<div class="thumbnails">
		<div class="inner clearfix">
		<?php if( $field['value'] ): foreach( $field['value'] as $attachment ): 
			
			$src = wp_get_attachment_image_src( $attachment['id'], $field['preview_size'] );
			$src = $src[0];
			
			?>
			<div class="thumbnail" data-id="<?php echo $attachment['id']; ?>">
				<input type="hidden" name="<?php echo $field['name']; ?>[]" value="<?php echo $attachment['id']; ?>" />
				<div class="inner clearfix">
					<img src="<?php echo $src; ?>" alt="" />
					<div class="list-data">
						<table>
							<tbody>
							<tr>
								<th><label><?php _e("Title",'acf'); ?>:</label></th>
								<td><?php echo $attachment['title']; ?></td>
							</tr>
							<tr>
								<th><label><?php _e("Alternate Text",'acf'); ?>:</label></th>
								<td><?php echo $attachment['alt']; ?></td>
							</tr>
							<tr>
								<th><label><?php _e("Caption",'acf'); ?>:</label></th>
								<td><?php echo $attachment['caption']; ?></td>
							</tr>
							<tr>
								<th><label><?php _e("Description",'acf'); ?>:</label></th>
								<td><?php echo $attachment['description']; ?></td>
							</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="hover">
					<ul class="bl">
						<li><a href="#" class="remove-image ir"><?php _e("Remove",'acf'); ?></a></li>
						<li><a href="#" class="edit-image ir"><?php _e("Edit",'acf'); ?></a></li>
					</ul>
				</div>
				
			</div>
		<?php endforeach; endif; ?>
		</div>
	</div>

	<div class="toolbar">
		<ul class="hl clearfix">
			<li class="add-image-li"><a class="add-image" href="#"><?php _e("Add Image",'acf'); ?></a><div class="divider"></div></li>
			<li class="view-grid-li active"><div class="divider divider-left"></div><a class="ir view-grid" href="#"><?php _e("Grid",'acf'); ?></a><div class="divider"></div></li>
			<li class="view-list-li"><a class="ir view-list" href="#"><?php _e("List",'acf'); ?></a><div class="divider"></div></li>
			<li class="count-li right">
				<span class="count" data-0="No images selected" data-1="1 image selected" data-2="{count} images selected"></span>
			</li>
		</ul>
	</div>
	
	<script type="text/html" class="tmpl-thumbnail">
	<div class="thumbnail" data-id="0">
		<input type="hidden" name="<?php echo $field['name']; ?>[]" value="" />
		<div class="inner clearfix">
			<img src="" alt="" />
			<div class="list-data"><!-- Generated by AJAX --></div>
		</div>
		<div class="hover">
			<ul class="bl">
				<li><a href="#" class="remove-image ir"><?php _e("Remove",'acf'); ?></a></li>
				<li><a href="#" class="edit-image ir"><?php _e("Edit",'acf'); ?></a></li>
			</ul>
		</div>
		
	</div>
	</script>
	
</div>
		<?php
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
		
		
		// empty?
		if( empty($value) )
		{
			return $value;
		}
		
		
		// find attachments (DISTINCT POSTS)
		$attachments = get_posts(array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post__in' => $value,
		));
		
		$ordered_attachments = array();
		foreach( $attachments as $attachment)
		{
			// create array to hold value data
			$ordered_attachments[ $attachment->ID ] = array(
				'id' => $attachment->ID,
				'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
				'title' => $attachment->post_title,
				'caption' => $attachment->post_excerpt,
				'description' => $attachment->post_content,
			);
		}
		
		
		// override value array with attachments
		foreach( $value as $k => $v)
		{
			$value[ $k ] = $ordered_attachments[ $v ];
		}
		
		
		/*
		// format attachments
		$ordered_attachments = array();
		foreach( $attachments as $attachment )
		{
			$ordered_attachments[ $attachment->ID ] = $attachment;
		}
		
		
		// update value with corisponding attachments
		foreach( $value as $k => $v)
		{
			// get the attachment onject for this value (attachment id)
			$attachment = $ordered_attachments[ $v ];
			
			// create array to hold value data
			$value[ $k ] = array(
				'id' => $attachment->ID,
				'alt' => get_post_meta($attachment->ID, '_wp_attachment_image_alt', true),
				'title' => $attachment->post_title,
				'caption' => $attachment->post_excerpt,
				'description' => $attachment->post_content,
			);
		}
		
		*/
		
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
		
		
		// find all image sizes
		$image_sizes = get_intermediate_image_sizes();


		if( $value )
		{
			foreach( $value as $k => $v )
			{
				// full url
				$value[$k]['url'] = wp_get_attachment_url( $v['id'] );
				
				// sizes
				if( $image_sizes )
				{
					$value[$k]['sizes'] = array();
					
					foreach( $image_sizes as $image_size )
					{
						// find src
						$src = wp_get_attachment_image_src( $v['id'], $image_size );
						
						// add src
						$value[$k]['sizes'][$image_size] = $src[0];
					}
					// foreach( $image_sizes as $image_size )
				}
				// if( $image_sizes )	
			}
			// foreach( $value as $k => $v )
		}
		// if( $value )
		
		
		// return value
		return $value;

	}
	
	
	/*
	*  popup_head
	*
	*  @description: 
	*  @since 3.2.8
	*  @created: 6/07/12
	*/
	
	function popup_head()
	{
		// $_GET is required
		if( ! isset($_GET) )
		{
			return;
		}
		
		
		// options
		$defaults = array(
			'acf_type' => '',
			'acf_gallery_id' => '',
			'preview_size' => 'thumbnail',
			'tab'	=>	'type',	
		);
		
		$options = array_merge($defaults, $_GET);
		
		
		// validate
		if( $options['acf_type'] != 'gallery' )
		{
			return;
		}

			
?><style type="text/css">
	#media-upload-header #sidemenu li#tab-type_url,
	#media-upload-header #sidemenu li#tab-gallery,
	#media-items .media-item a.toggle,
	#media-items .media-item tr.image-size,
	#media-items .media-item tr.align,
	#media-items .media-item tr.url,
	#media-items .media-item .slidetoggle {
		display: none !important;
	}
	
	#media-items .media-item {
		position: relative;
		overflow: hidden;
	}
	
	#media-items .media-item .acf-checkbox {
		float: left;
		margin: 28px 10px 0;
	}
	
	#media-items .media-item .pinkynail {
		max-width: 64px;
		max-height: 64px;
		display: block !important;
		margin: 2px;
	}
	
	#media-items .media-item .filename.new {
		min-height: 0;
		padding: 20px 10px 10px 10px;
		line-height: 15px;
	}
	
	#media-items .media-item .title {
		line-height: 14px;
	}
	
	#media-items .media-item .acf-select {
		float: right;
		margin: 22px 12px 0 10px;
	}
	
	#media-upload .ml-submit {
		display: none !important;
	}

	#media-upload .acf-submit {
		margin: 1em 0;
		padding: 1em 0;
		position: relative;
		overflow: hidden;
		display: none; /* default is hidden */
	}
	
	#media-upload .acf-submit a {
		float: left;
		margin: 0 10px 0 0;
	}
	
	.acf-message-wrapper .message {
		margin: 2px 2px 0;
	}

</style>
<script type="text/javascript">
(function($){
	
	
	// global vars
	var gallery,
		tmpl_thumbnail;
	
	

	/*
	*  Select Image
	*
	*  @created : 28/03/2012
	*/
	
	$('#media-items .media-item a.acf-select').live('click', function(){
		
		var attachment_id = $(this).attr('href'),
			div = $(this).closest('.media-item');
		
		
		// does this image already exist in the gallery?
		if( gallery.find('.thumbnail[data-id="' + attachment_id + '"]').length > 0 )
		{
			alert("<?php _e("Image already exists in gallery",'acf'); ?>");
			return false;
		}
		
		
		// show added message
		self.parent.acf.add_message('<?php _e("Image Added",'acf'); ?>.', div);
		
		
		// add image
		add_image( attachment_id );
		
		
		return false;
		
	});
	
	
	// edit toggle
	$('#media-items .media-item a.acf-toggle-edit').live('click', function(){
		
		if( $(this).hasClass('active') )
		{
			$(this).removeClass('active');
			$(this).closest('.media-item').find('.slidetoggle').attr('style', 'display: none !important');
			return false;
		}
		else
		{
			$(this).addClass('active');
			$(this).closest('.media-item').find('.slidetoggle').attr('style', 'display: table !important');
			return false;
		}
		
	});
	
	
	// set a interval function to add buttons to media items
	function acf_add_buttons()
	{

		// add buttons to media items
		$('#media-items .media-item:not(.acf-active)').each(function(){
			
			// needs attachment ID
			if($(this).children('input[id*="type-of-"]').length == 0){ return false; }
			
			// only once!
			$(this).addClass('acf-active');
			
			// find id
			var id = $(this).children('input[id*="type-of-"]').attr('id').replace('type-of-', '');
			
			// Add edit button
			$(this).find('.filename.new').append('<br /><a href="#" class="acf-toggle-edit">Edit</a>');
			
			// Add select button
			$(this).find('.filename.new').before('<a href="' + id + '" class="button acf-select"><?php _e("Add Image",'acf'); ?></a>');
			
			// add save changes button
			$(this).find('tr.submit input.button').hide().before('<input type="submit" value="<?php _e("Update Image",'acf'); ?>" class="button savebutton" />');

			
		});
	}
	<?php
	
	// run the acf_add_buttons ever 500ms when on the image upload tab
	if($options['tab'] == 'type'): ?>
	var acf_t = setInterval(function(){
		acf_add_buttons();
	}, 500);
	<?php endif; ?>
	
	
	// add acf input filters to allow for tab navigation
	$(document).ready(function(){
		
		setTimeout(function(){
			acf_add_buttons();
		}, 1);
		
		
		$('form#filter').each(function(){
			
			$(this).append('<input type="hidden" name="acf_preview_size" value="<?php echo $options['preview_size']; ?>" />');
			$(this).append('<input type="hidden" name="acf_type" value="gallery" />');
						
		});
		
		$('form#image-form, form#library-form').each(function(){
			
			var action = $(this).attr('action');
			action += "&acf_type=gallery&acf_preview_size=<?php $options['preview_size']; ?>";
			$(this).attr('action', action);
			
		});
	});
	
	
	/*
	*  add_image
	*
	*  @description: 
	*  @created: 2/07/12
	*/
	
	function add_image( attachment_id )
	{
			
		$.ajax({
			url: ajaxurl,
			data : {
				action: 'acf_get_preview_image',
				id: attachment_id,
				preview_size : "<?php echo $options['preview_size']; ?>"
			},
			cache: false,
			dataType: "json",
			success: function( json ) {
		    	

				// validate
				if(!json)
				{
					return;
				}
				
				
				// get item
				var item = json[0];
				
				
				// create thumbnail div
			    var div = $(tmpl_thumbnail);
			     
			     
			    // add id refernece to later replace this div with the image
			    div.attr('data-id', item.id);
			    div.find('input[type="hidden"]').val(item.id);
			    
			    
			    // add image src
			    div.find('img').attr('src', item.url);
			    
			    
			    // replace loading with thumbnail
			    gallery.find('.thumbnails > .inner').append( div );
				
				
				// update gallery count
				self.parent.acf.update_gallery_count( gallery );
				
				
				// validation
	 			div.closest('.field').removeClass('error');
	 			
				
				// ajax find new list data
				$.ajax({
					url: ajaxurl,
					data : {
						'action' : 'acf_get_gallery_list_data',
						'attachment_id' : item.id
					},
					cache: false,
					dataType: "html",
					success: function( html ) {
				    	
			
						// validate
						if(!html)
						{
							return false;
						}
						
						
						// update list-item html
						div.find('.list-data').html( html ); 	
			 	
					}
					// success: function( html ) {
					
				});
				// $.ajax({
			}
			// success: function( json ) {
		});
		// $.ajax({
	}
	
	
	/*
	*  Document Ready
	*
	*  @description: 
	*  @created: 2/07/12
	*/	
	
	$(document).ready(function(){
		
		
		// vars
		gallery = self.parent.acf_div;
		tmpl_thumbnail = gallery.find('.tmpl-thumbnail').html();
		
		
	});
				
})(jQuery);
</script><?php

	}
	
}

?>
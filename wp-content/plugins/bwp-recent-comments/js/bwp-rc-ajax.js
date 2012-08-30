jQuery(document).ready(function(){
	jQuery('.bwp-rc-older-nav').live('click', function(){
		container 	= jQuery(this).parents('.bwp-rc-ajax-nav');
		var data = {
			bwp_doing_ajax: 1,
			bwp_rc_action: 'older',
			bwp_rc_instance: container.find('input[name="bwp_rc_instance"]').val(),
			bwp_rc_paged: container.find('input[name="bwp_rc_paged"]').val(),
			bwp_rc_paged_limit: container.find('input[name="bwp_rc_paged_limit"]').val(),
			bwp_rc_paged_template: container.find('input[name="bwp_rc_paged_template"]').val()
		};
		jQuery.get(bwp_ajax_url, data, function(response) {
			container.parents('.bwp-rc-ulist').html(response);
		});
		return false;
	});
	jQuery('.bwp-rc-newer-nav').live('click', function(){
		container 	= jQuery(this).parents('.bwp-rc-ajax-nav');
		var data = {
			bwp_doing_ajax: 1,
			bwp_rc_action: 'newer',
			bwp_rc_instance: container.find('input[name="bwp_rc_instance"]').val(),
			bwp_rc_paged: container.find('input[name="bwp_rc_paged"]').val(),
			bwp_rc_paged_limit: container.find('input[name="bwp_rc_paged_limit"]').val(),
			bwp_rc_paged_template: container.find('input[name="bwp_rc_paged_template"]').val()
		};
		jQuery.get(bwp_ajax_url, data, function(response) {
			container.parents('.bwp-rc-ulist').html(response);
		});
		return false;
	});
});
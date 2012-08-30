jQuery(document).ready(function(){
	jQuery('.bwp-rc-instance-switch').live('change', function(){					
		jQuery(this).parents('.bwp-rc-widget-control').find('.bwp-rc-instance-settings').toggle('fast');
		return false;
	});
});
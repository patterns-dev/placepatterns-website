<?php
/**
 * Copyright (c) 2011 Khang Minh <betterwp.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU GENERAL PUBLIC LICENSE VERSION 3.0 OR LATER
 */

/**
 * BWP Recent Comments Widget Class
 */
class BWP_RC_Widget extends WP_Widget
{
	function BWP_RC_Widget()
	{
		$widget_ops = array('classname' => 'bwp-rc-widget', 'description' => __( 'Show a list of recent comments/trackbacks generated by the BWP Recent Comments plugin.', 'bwp-rc') );
		$control_ops = array('width' => 350);
		$this->WP_Widget('bwp_recent_comments', __('BWP Recent Comments', 'bwp-rc'), $widget_ops, $control_ops);
	}

	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Recent Comments', 'bwp-rc') : $instance['title'], $instance, $this->id_base);			

		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title . "\n";

		if (function_exists('bwp_get_recent_comments'))
		{
?>
		<ul class="bwp-rc-ulist">
<?php bwp_get_recent_comments($instance); ?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update($new_instance, $old_instance)
	{
		global $bwp_rc;

		$use_settings = (isset($new_instance['use_settings'])) ? true : false;
		$instances = $bwp_rc->get_instances();
		if (isset($new_instance['instance']))
			$new_instance['instance'] = $bwp_rc->format_instance_name($new_instance['instance']);

		if (true == $use_settings)
		{
			$the_instance = 'bwp_rc_instance_' . str_replace(' ', '_', $new_instance['instance']);
			if (!empty($instances[$the_instance]))
				$instance = $instances[$the_instance];
			else
			{
				$instance = wp_parse_args((array) $new_instance, $bwp_rc->get_default_parameters());
				$instance['separate'] = (isset($new_instance['separate'])) ? true : false;
				$instance['ajax'] = (isset($new_instance['ajax'])) ? true : false;
			}
		}
		else
		{
			$instance = wp_parse_args((array) $new_instance, $bwp_rc->get_default_parameters());
			$instance['separate'] = (isset($new_instance['separate'])) ? true : false;
			$instance['ajax'] = (isset($new_instance['ajax'])) ? true : false;
		}

		$instance['post_id'] = trim($instance['post_id']);
		$instance['limit'] = (int) $instance['limit'];
		$instance['tb_limit'] = (int) $instance['tb_limit'];
		$instance['grouped'] = (int) $instance['grouped'];
		$tb_limit = (empty($instance['tb_limit']) && !empty($bwp_rc->options['input_tbs'])) ? $bwp_rc->options['input_tbs'] : $instance['tb_limit'];		
		$instance['separate'] = (!empty($tb_limit) && $instance['separate'] == true) ? true : false;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['instance'] = strip_tags($new_instance['instance']);

		if (empty($instance['instance']))
			$instance['instance'] = BWP_RC_LIST;

		bwp_get_recent_comments($instance, false, true);

		return $instance;
	}

	function form($instance)
	{
		global $bwp_rc;

		$instance = wp_parse_args((array) $instance, $bwp_rc->get_default_parameters());
		$instance_name = strip_tags($instance['instance']);
		if ($instance_name == BWP_RC_LIST)
			$instance_name = '';
		$title = (isset($instance['title'])) ? strip_tags($instance['title']) : '';
		$limit = (int) $instance['limit'];
		$tb_limit = (int) $instance['tb_limit'];
		$post_types = get_post_types(array('public' => true), 'objects');
		$post_id = $instance['post_id'];
		$grouped = (int) $instance['grouped'];
?>
		<div class="bwp-rc-widget-control">
		<p><?php _e('<strong>Note:</strong> All inputs are optional.', 'bwp-rc'); ?></p>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'bwp-rc'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<p><label for="<?php echo $this->get_field_id('instance'); ?>"><?php _e('Instance Name (no special characters):', 'bwp-rc'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('instance'); ?>" name="<?php echo $this->get_field_name('instance'); ?>" type="text" value="<?php echo esc_attr($instance_name); ?>" /></p>
		<p>
			<input class="checkbox bwp-rc-instance-switch" type="checkbox" checked="checked" id="<?php echo $this->get_field_id('use_settings'); ?>" name="<?php echo $this->get_field_name('use_settings'); ?>" /> <label for="<?php echo $this->get_field_id('use_settings'); ?>"><?php _e('Use saved settings for this instance', 'bwp-rc'); ?></label>
		</p>
		<fieldset class="bwp-rc-instance-settings">
		<legend><strong><?php _e('Instance Settings', 'bwp-rc'); ?></strong></legend>
		<p>
			<label for="<?php echo $this->get_field_id('post_type'); ?>"><?php _e('Post Type:', 'bwp-rc'); ?></label>
			<select id="<?php echo $this->get_field_id('post_type'); ?>" name="<?php echo $this->get_field_name('post_type'); ?>">
				<option value=""><?php _e('&mdash; Select a Post Type &mdash;', 'bwp-rc'); ?></option>
<?php foreach ($post_types as $post_type) { ?>
				<option value="<?php echo $post_type->name; ?>" <?php selected($instance['post_type'], $post_type->name ); ?>><?php echo $post_type->label; ?></option>
<?php } ?>				
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_id'); ?>"><?php _e('Post ID or name (slug)', 'bwp-rc'); ?> <input class="smallfat" style="width: 180px;" id="<?php echo $this->get_field_id('post_id'); ?>" name="<?php echo $this->get_field_name('post_id'); ?>" type="text" value="<?php echo esc_attr($post_id); ?>" /></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('comment_type'); ?>"><?php _e('Comment Type:', 'bwp-rc'); ?></label>
			<select id="<?php echo $this->get_field_id('comment_type'); ?>" name="<?php echo $this->get_field_name('comment_type'); ?>">
				<option value="all"><?php _e('&mdash; Select a Comment Type &mdash;', 'bwp-rc'); ?></option>
				<option value="comment" <?php selected($instance['comment_type'], 'comment' ); ?>><?php _e('Comments only', 'bwp-rc'); ?></option>
				<option value="tb" <?php selected($instance['comment_type'], 'tb' ); ?>><?php _e('Trackbacks/pingbacks only', 'bwp-rc'); ?></option>				
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('grouped'); ?>"><?php _e('Group at most', 'bwp-rc'); ?> <input class="smallfat" id="<?php echo $this->get_field_id('grouped'); ?>" name="<?php echo $this->get_field_name('grouped'); ?>" type="text" value="<?php echo esc_attr($grouped); ?>" /> <?php _e('comments each post (0 to disable)', 'bwp-rc'); ?></label>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('order'); ?>"><?php _e('Show', 'bwp-rc'); ?></label>
			<select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>">
				<option value="desc" <?php selected($instance['order'], 'desc' ); ?>><?php _e('Newer comments first', 'bwp-rc'); ?></option>
				<option value="asc" <?php selected($instance['order'], 'asc' ); ?>><?php _e('Older comments first', 'bwp-rc'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('limit'); ?>"><?php _e('Show', 'bwp-rc'); ?> <input class="smallfat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo esc_attr($limit); ?>" /> <?php _e('comments and', 'bwp-rc'); ?></label>
			<label for="<?php echo $this->get_field_id('tb_limit'); ?>"><input class="smallfat" id="<?php echo $this->get_field_id('tb_limit'); ?>" name="<?php echo $this->get_field_name('tb_limit'); ?>" type="text" value="<?php echo esc_attr($tb_limit); ?>" />  <?php _e('trackbacks', 'bwp-rc'); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['separate'], true); ?> id="<?php echo $this->get_field_id('separate'); ?>" name="<?php echo $this->get_field_name('separate'); ?>" /> <label for="<?php echo $this->get_field_id('separate'); ?>"><?php _e('Separate comments and trackbacks', 'bwp-rc'); ?></label>
		</p>
		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['ajax'], true); ?> id="<?php echo $this->get_field_id('ajax'); ?>" name="<?php echo $this->get_field_name('ajax'); ?>" /> <label for="<?php echo $this->get_field_id('ajax'); ?>"><?php printf(__('Enable AJAX navgiation &ndash; <a href="%s">more info</a>', 'bwp-rc'), $bwp_rc->plugin_url . '#ajax_navigation'); ?></label>
		</p>
		</fieldset>
		</div>
<?php
	}
}

function bwp_recent_comment_register_widget()
{	
	register_widget('BWP_RC_Widget');
	if (is_admin())
		wp_enqueue_script('bwp-rc-js', BWP_RC_JS . '/bwp-rc-widget.js', array('jquery'));
}
?>
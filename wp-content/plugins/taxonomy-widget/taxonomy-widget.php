<?php
/*
Plugin Name:    Taxonomy Widget
Plugin URI:     http://wordpress.org/extend/plugins/taxonomy-widget/
Description:    Display taxonomy terms in your sidebar.
Version:        0.6.1
Author:         Michael Fields
Author URI:     http://wordpress.mfields.org/

Copyright 2009-2011  Michael Fields  michael@mfields.org

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


function mfields_taxonomy_widget_register() {
	$taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
	if ( empty( $taxonomies ) ) {
		return;
	}
	register_widget( 'Mfields_Taxonomy_Widget' );
}
add_action( 'widgets_init', 'mfields_taxonomy_widget_register' );


class Mfields_Taxonomy_Widget extends WP_Widget {
	static $listeners = array();
	var $templates = array();
	var $taxonomies = array();
	var $javascript_has_been_printed = false;
	var $event_handlers = array();
	var $default_args = array(
		'count'         => 0,
		'display_title' => 1,
		'hierarchical'  => 0,
		'taxonomy'      => 'category',
		'template'      => 'ul',
		'title'         => '',
		);

	function Mfields_Taxonomy_Widget() {

		/* Configuration. */
		$this->WP_Widget( 'taxonomy', __( 'Taxonomy', 'mfields-taxonomy-widget' ), array(
			'classname'   => 'widget_taxonomy',
			'description' => __( 'Create a list, dropdown or term cloud of any taxonomy.', 'mfields-taxonomy-widget' )
		) );

		/* Supported templates. */
		$this->templates = array (
			'cloud'    => __( 'Cloud', 'mfields-taxonomy-widget' ),
			'dropdown' => __( 'Dropdown', 'mfields-taxonomy-widget' ),
			'ol'       => __( 'Ordered List', 'mfields-taxonomy-widget' ),
			'ul'       => __( 'Unordered List', 'mfields-taxonomy-widget' ),
		);

		/* Custom CSS is for logged-in users only. */
		if ( current_user_can( 'edit_theme_options' ) ) {
			add_action( 'admin_head-widgets.php', array( &$this, 'css_admin' ) );
			add_action( 'admin_head-widgets.php', array( &$this, 'css_dialog' ) );
			add_action( 'wp_head', array( &$this, 'css_dialog' ) );
		}

		add_action( 'wp_loaded', array( &$this, 'set_taxonomies' ) );
		add_action( 'wp_footer', array( &$this, 'listeners_print' ) );
	}

	/**
	 * Set the "taxonomies" property.
	 *
	 * Value should contain all public taxonomies registered with WordPress.
	 * This function should fire sometime after the 'init' hook.
	 *
	 * @since      0.6.1
	 */
	public function set_taxonomies() {
		$this->taxonomies = get_taxonomies( array( 'public' => 1 ), 'objects' );

		$names = array_keys( $this->taxonomies );
		if ( ! in_array( 'category', $names ) ) {
			$this->default_args['taxonomy'] = $names[0];
		}
	}

	function css_admin() {
		print <<<EOF
<style type="text/css">
.mfields-taxonomy-widget-admin .heading,
.mfields-taxonomy-widget-admin legend {
	font-weight: bold;
	}
.mfields-taxonomy-widget-admin fieldset{
	margin:1em 0;
	}
</style>
EOF;
	}

	function css_dialog() {
		print <<<EOF
<style type="text/css">
.dialog {
	padding:.5em .75em;
	margin:1em;
	border:.25em dotted #acd2e5;
	background-color:#bfeaff;
	width:100%;
}
.dialog.mfields-taxonomy-widget-error    { background-color:#ffd9d9; border-color:#e5b8b8; }
.dialog.mfields-notice   { background-color:#fffabf; border-color:#f2e76d }
.dialog.mfields-success  { background-color:#bfffc5; border-color:#a3d9a7 }
</style>
EOF;
	}

	function listeners_add( $id ) {
		if ( isset( $id ) && ! in_array( $id, self::$listeners ) ) {
			self::$listeners[] = $id;
		}
	}

	function listeners_print() {
		$url = get_option( 'home' );

		$listeners = array();
		foreach ( self::$listeners as $id ) {
			$listeners[] = 'document.getElementById( "' . $id . '" ).onchange = changeTaxonomy;';
		}
		$listeners = join( "\n", $listeners );
		
print <<<EOF
<script type='text/javascript'>
/* <![CDATA[ */
function changeTaxonomy() {
	if ( 0 == this.options[this.selectedIndex].value ) {
		return;
	}
	if ( 0 == this.options[this.selectedIndex].value ) {
		return;
	}
	location.href = this.options[this.selectedIndex].value;
}
$listeners
/* ]]> */
</script>
EOF;
	}

	function clean_args( $args ) {
		/*
		 * Merge $args into defaults.
		 * wp_parse_args() works much like array_merge() only the argument order is reversed.
		 */
		$args = wp_parse_args( $args, $this->default_args );

		$clean = array();
		foreach ( (array) $args as $key => $value ) {
			switch ( $key ) {
				/* Title */
				case 'title' :
					$clean[$key] = trim( strip_tags( $value ) );
					break;
				
				/* Taxonomy */
				case 'taxonomy' :	
					$clean[$key] = 'category';
					if ( array_key_exists( $value, $this->taxonomies ) ) {
						$clean[$key] = $value;
					}
					break;
				
				/* Template */
				case 'template' :
					$clean[$key] = 'ul';
					if ( array_key_exists( $value, $this->templates ) ) {
						$clean[$key] = $value;
					}
					break;
					
				/* Boolean */
				default :
					$clean[$key] = (bool) $value;
					break;
			}
		}
		return $clean;
	}

	function widget( $args, $instance ) {
		extract( $args );
		extract( $this->clean_args( $instance ) );

		$taxonomy_object = get_taxonomy( $taxonomy );

		/*
		 * Return early if taxonomy does not support clouds.
		 */
		if ( 'cloud' == $template && isset( $taxonomy_object->show_tagcloud ) && empty( $taxonomy_object->show_tagcloud ) ) {
			if ( current_user_can( 'edit_theme_options' ) ) {
				print '<div class="dialog mfields-taxonomy-widget-error">';
				print $before_title . sprintf( esc_html__( 'Taxonomy Widget Error', 'mfields-taxonomy-widget' ) ) . $after_title;
				if ( isset( $taxonomy_object->label ) && ! empty( $taxonomy_object->label ) ) {
					printf( esc_html__( 'Term clouds are not supported for &#8220;%1$s&#8221;.', 'mfields-taxonomy-widget' ), $taxonomy_object->label );
				}
				else {
					printf( esc_html__( 'Term clouds are not supported for this taxonomy.', 'mfields-taxonomy-widget' ) );
				}
				print '</div>';
			}
			return;
		}

		$title = apply_filters( 'widget_title', $title );

		print $before_widget;

		if ( ! empty( $title ) ) {
			print $before_title . $title . $after_title;
		}

		$taxonomy_args = apply_filters( 'mfields_taxonomy_widget_args_global', array(
			'hierarchical' => $hierarchical,
			'orderby'      => 'name',
			'show_count'   => $count,
			'taxonomy'     => $taxonomy
			) );

		switch ( $template ) {

			case 'dropdown' :

				$term = get_queried_object();

				$show_option_none = __( 'Please Choose', 'mfields-taxonomy-widget' );
				$show_option_none = apply_filters( 'taxonomy-widget-show-option-none', $show_option_none );
				$show_option_none = apply_filters( 'taxonomy-widget-show-option-none-' . $taxonomy, $show_option_none );
				$show_option_none = esc_attr( $show_option_none );

				$selected = null;
				if ( isset( $term->taxonomy ) ) {
					$selected = get_term_link( $term, $term->taxonomy );
				}

				/* Arguments specific to wp_dropdown_categories(). */
				$dropdown_args = array(
					'id'               => $this->get_field_id( 'mfields_taxonomy_widget_dropdown_wrapper' ),
					'name'             => $taxonomy_object->query_var,
					'selected'         => $selected,
					'show_option_none' => $show_option_none,
					'walker'           => new Mfields_Walker_Taxonomy_Dropdown(),
					);

				$args = array_merge( $taxonomy_args, $dropdown_args );

				wp_dropdown_categories( $args );

				$this->listeners_add( $args['id'] );

				break;

			case 'cloud' :
				wp_tag_cloud( apply_filters( 'mfields_taxonomy_widget_args_cloud', $taxonomy_args ) );
				break;

			case 'ol' :
			case 'ul' : 
			default :
				$tag = 'ul';
				if ( $template == 'ol' ) {
					$tag = 'ol';
				}
				print "\n\t" . '<' . $tag . '>';
				$taxonomy_args['title_li'] = '';
				wp_list_categories( apply_filters( 'mfields_taxonomy_widget_args_list', $taxonomy_args ) );
				print "\n\t" . '</' . $tag . '>';
				break;
		}
		print $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		return $this->clean_args( $new_instance );
	}

	function form( $instance ) {
		extract( $this->clean_args( $instance ) );

		print "\n\t" . '<div class="mfields-taxonomy-widget-admin">';

		/*
		 * Widget Title.
		 */
		print "\n\t" . '<p><label for="' . esc_attr( $this->get_field_id( 'title' ) ) . '" class="heading">' . esc_html__( 'Title:', 'mfields-taxonomy-widget' ) . '</label>';
		print "\n\t" . '<input class="widefat" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" /></p>';

		/*
		 * Choose a Taxonomy.
		 */
		$id = $this->get_field_id( 'taxonomy' );
		print "\n\t" . '<label class="heading" for="' . esc_attr( $id ) . '">' . esc_html__( 'Choose Taxonomy to Display:', 'mfields-taxonomy-widget' ) . '</label>';
		print "\n\t" . '<select name="' . esc_attr( $this->get_field_name( 'taxonomy' ) ) . '" id="' . esc_attr( $id ) . '" class="widefat">';
		foreach ( $this->taxonomies as $slug => $taxonomy ) {
			if ( isset( $taxonomy->label ) && ! empty( $taxonomy->label ) ) {
				print "\n\t" . '<option value="' . esc_attr( $slug ) . '" ' . selected( $slug, $instance['taxonomy'], false ) . '>' . esc_html( $taxonomy->label ) . '</option>';
			}
		}
		print "\n\t" . '</select>';

		/*
		 * Display Taxonomy As.
		 */
		print "\n\t" . '<fieldset><legend>' . esc_html__( 'Display Taxonomy As:', 'mfields-taxonomy-widget' ) . '</legend>';
		foreach( $this->templates as $name => $label ) {
			$id = $this->get_field_id( 'template' ) . '-' . $name;
			print "\n\t" . '<input type="radio" name="' . esc_attr( $this->get_field_name( 'template' ) ) . '" value="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" ' . checked( $name, $template, false ) . ' />';
			print "\n\t" . '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br />';
		}
		print  "\n\t" . '</fieldset>';

		print "\n\t" . '<fieldset><legend>' . esc_html__( 'Advanced Options', 'mfields-taxonomy-widget' ) . '</legend>';

		/*
		 * Display Title?
		 */
		print "\n\t" . '<input type="checkbox" class="checkbox" id="' . esc_attr( $this->get_field_id( 'display_title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'display_title' ) ) . '"' . checked( $display_title, true, false ) . ' />';
		print "\n\t" . '<label for="' . esc_attr( $this->get_field_id( 'display_title' ) ) . '">' . esc_html__( 'Display Title', 'mfields-taxonomy-widget' ) . '</label><br />';

		/*
		 * Show Post Counts?
		 */
		print "\n\t" . '<input type="checkbox" class="checkbox" id="' . esc_attr( $this->get_field_id( 'count' ) ) . '" name="' . esc_attr( $this->get_field_name( 'count' ) ) . '"' . checked( $count, true, false ) . ' />';
		print "\n\t" . '<label for="' . esc_attr( $this->get_field_id( 'count' ) ) . '">' . esc_html__( 'Show post counts', 'mfields-taxonomy-widget' ) . '</label><br />';

		/*
		 * Show Hierarchy?
		 */
		print "\n\t" . '<input type="checkbox" class="checkbox" id="' . esc_attr( $this->get_field_id( 'hierarchical' ) ) . '" name="' . esc_attr( $this->get_field_name( 'hierarchical' ) ) . '"' . checked( $hierarchical, true, false ) . ' />';
		print "\n\t" . '<label for="' . esc_attr( $this->get_field_id( 'hierarchical' ) ) . '">' . esc_html__( 'Show hierarchy', 'mfields-taxonomy-widget' ) . '</label>';

		print "\n\t" . '</fieldset>';
		print "\n\t" . '</div>';
	}
}


/* Custom version of Walker_CategoryDropdown */
class Mfields_Walker_Taxonomy_Dropdown extends Walker {
	var $db_fields = array(
		'id'     => 'term_id',
		'parent' => 'parent'
		);

	var $tree_type = 'category';

	function start_el( &$output, $term, $depth, $args ) {
		$url = get_term_link( $term, $term->taxonomy );

		$selected = '';
		if ( $url == $args['selected'] ) {
			$selected .= ' selected="selected"';
		}

		$text = str_repeat( '&nbsp;', $depth * 3 ) . $term->name;
		if ( $args['show_count'] ) {
			$text .= '&nbsp;&nbsp;('. $term->count .')';
		}
		if ( $args['show_last_update'] ) {
			$text .= '&nbsp;&nbsp;' . gmdate( __( 'Y-m-d', 'mfields-taxonomy-widget' ), $term->last_update_timestamp );
		}

		$class_name = 'level-' . $depth;

		$output.= "\t" . '<option' . $selected . ' class="' . esc_attr( $class_name ) . '" value="' . esc_url( $url ) . '">' . esc_html( $text ) . '</option>' . "\n";
	}
}

<?php
/*
Plugin Name: Custom Function for Waters Edge
Plugin URI: http://marquex.es/541/custom-sidebars-plugin-v0-8
Description: Custom Functions.
Version: 0.8.2
Author: Javier Marquez
Author URI: http://marquex.es
*/

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override boilerplate_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function boilerplate_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Header Widget Area', 'boilerplate' ),
		'id' => 'header-widget-area',
		'description' => __( 'The header widget area', 'boilerplate' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Featured Widget Area', 'boilerplate' ),
		'id' => 'featured-widget-area',
		'description' => __( 'The featured widget area', 'boilerplate' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Left Sidebar Widget Area', 'boilerplate' ),
		'id' => 'left-sidebar-widget-area',
		'description' => __( 'The left sidebar widget area', 'boilerplate' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Right Sidebar Widget Area', 'boilerplate' ),
		'id' => 'right-sidebar-widget-area',
		'description' => __( 'The right sidebar widget area', 'boilerplate' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Footer Widget Area', 'boilerplate' ),
		'id' => 'footer-widget-area',
		'description' => __( 'The footer sidebar widget area', 'boilerplate' ),
		'before_widget' => '<div id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );

	
}
/** Register sidebars by running boilerplate_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'boilerplate_widgets_init' );
?>
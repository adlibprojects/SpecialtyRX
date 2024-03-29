<?php
/**
 * @package Duplicate_Widget
 * @author Scott Reilly
 * @version 1.0.1
 */
/*
Plugin Name: Duplicate Widget
Version: 1.0.1
Plugin URI: http://coffee2code.com/wp-plugins/duplicate-widget/
Author: Scott Reilly
Author URI: http://coffee2code.com
Text Domain: duplicate-widget
Domain Path: /lang/
Description: A widget that can act as a duplicate of another widget (for synchronized use in another sidebar)

Compatible with WordPress 3.1+, 3.2+, 3.3+

=>> Read the accompanying readme.txt file for instructions and documentation.
=>> Also, visit the plugin's homepage for additional information and updates.
=>> Or visit: http://wordpress.org/extend/plugins/duplicate-widget/

TODO:
	* More dynamic updating via JS (maybe)
		* Add "[D]" and widget blurb (if not present) to widget after it is selected as the source widget for a duplicate
		* Remove "[D]" and widget blurb if final duplicate of widget is deleted
		* Immediately delete duplicates of a widget that gets deleted or deactivated
	* When clicking the dropdown, can check some JS flag that gets set when a widget gets activated/deactivated which causes the dropdown to be regenerated via AJAX (or simply always refresh it via AJAX)

*/

/*
Copyright (c) 2010-2012 by Scott Reilly (aka coffee2code)

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR
IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

if ( ! class_exists( 'c2c_DuplicateWidget' ) ) :

class c2c_DuplicateWidget extends WP_Widget {

	private static $title          = '';
	private static $current        = null;
	private static $widget_id      = 'duplicate_widget';
	private static $textdomain     = 'duplicate-widget';
	private static $input_name     = 'widget_to_duplicate';

	// For memoization
	private static $_duplicates    = array();
	private static $_sidebar_names = array();
	private static $_titles        = array();
	private static $_widgets       = array();

	/**
	 * Returns version of the plugin.
	 */
	public static function version() {
		return '1.0.1';
	}

	/**
	 * Initializes the widget.
	 */
	public static function init() {
		// Register the widget
		add_action( 'widgets_init',     array( __CLASS__, '__register' ) );
		// Output message at bottom of widgets that have been duplicated to make note of such
		add_action( 'in_widget_form',   array( __CLASS__, 'note_duplicates' ), 10, 3 );
		// Load localization
		add_action( 'admin_init',       array( __CLASS__, 'load_textdomain' ) );
		// Stuff to only do on the widgets admin page
		add_action( 'load-widgets.php', array( __CLASS__, 'widgets_page_actions' ) );
	}

	/**
	 * Registers the widget.
	 */
	public static function __register() {
		register_widget( __CLASS__ );
	}

	/**
	 * Loads the localization textdomain for the plugin.
	 *
	 * Translations go into 'lang' sub-directory.
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( self::$textdomain, false, basename( dirname( __FILE__ ) ) . DIRECTORY_SEPARATOR . 'lang' );
	}

	/**
	 * Triggers hooks intended to fire on admin widgets.php page.
	 */
	public static function widgets_page_actions() {
		// Enqueue JS to make an indicator in titlebar of duplicated widgets
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_js' ) );
		// Clean up any duplicates whose source widget is no longer active
		add_action( 'admin_init',            array( __CLASS__, 'delete_lame_dupes' ) );
	}

	/**
	 * Enqueues admin JS to make an indicator in titlebar of duplicated widgets.
	 */
	public static function enqueue_admin_js() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( self::$widget_id, plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), '0.1', true );
		$strings = array(
			'widget_is_duplicated' => __( 'This widget is duplicated elsewhere.', self::$textdomain ),
			'notation'             => __( '[D]', self::$textdomain )
		);
		wp_localize_script( self::$widget_id, self::$widget_id, $strings );
	}

	/**
	 * Returns a list of all active, non-duplicate widgets across all sidebars.
	 *
	 * Also incidentally stores a listing of all duplicate widgets.
	 *
	 * @return array Array of all widgets
	 */
	public static function get_widgets() {
		if ( ! empty( self::$_widgets ) ) // Return memoized result if it's there
			return self::$_widgets;

		// Note: wp_get_sidebars_widgets() is noted as being a private function.
		// I'd rather use it than to access data from globals and recreate logic
		$sidebars_with_widgets = wp_get_sidebars_widgets();
		$sidebars_widgets      = array();
		$duplicate_widgets     = array();

		foreach ( $sidebars_with_widgets as $sidebar => $widgets ) {
			if ( 'wp_inactive_widgets' == $sidebar || is_null( $widgets ) )
				continue;

			foreach ( $widgets as $widget ) {
				if ( strpos( $widget, self::$widget_id . '-' ) !== 0 )
					$sidebars_widgets[] = $widget;
				else // Record the duplicate widgets for possible other use
					$duplicate_widgets[] = $widget;
			}
		}

		self::$_duplicates = $duplicate_widgets;
		self::$_widgets    = $sidebars_widgets;
		return self::$_widgets;
	}

	/**
	 * Returns an array of all duplicate widgets.
	 *
	 * Note: get_widgets() actually obtains and stores all duplicate widgets,
	 * so this method defers to that one.
	 *
	 * @return array All duplicate widgets
	 */
	public static function get_duplicate_widgets() {
		self::get_widgets();
		return self::$_duplicates;
	}

	/**
	 * Returns the title for the specified active widget.
	 *
	 * @param string $widget_id The id of the widget
	 * @return string The title for the specified active widget
	 */
	public static function get_widget_title( $widget_id ) {
		global $wp_registered_widgets;

		if ( empty( $widget_id ) || ! isset( $wp_registered_widgets[$widget_id] ) )
			return;

		if ( isset( self::$_titles[$widget_id] ) )
			return self::$_titles[$widget_id];

		$widget_num = explode( '-', $widget_id );
		$widget_num = end( $widget_num );

		$instance = get_option( $wp_registered_widgets[$widget_id]['callback'][0]->option_name );

		if ( is_array( $instance ) && array_key_exists( $widget_num, $instance ) )
			$title = $instance[$widget_num]['title'];
		else
			$title = '';

		self::$_titles[$widget_id] = $title;
		return $title;
	}

	/**
	 * Returns the markup for the <select> HTML tag and its <option> tags to
	 * select from the active widgets to duplicate.
	 *
	 * @param array $widgets (optional) The widgets to list in the dropdown. If not provided, get_widgets() will be called to get all active widgets.
	 * @param string $value (optional) The current value selected
	 * @return string The HTML for the <select>
	 */
	public function get_widgets_dropdown( $widgets = array(), $value = '' ) {
		global $wp_registered_widgets;

		if ( empty( $widgets ) )
			$widgets = self::get_widgets();

		$opt        = self::$input_name;
		$input_id   = $this->get_field_id( $opt );
		$input_name = $this->get_field_name( $opt );

		$html = "<select name='$input_name' id='$input_id'>\n";
		$html .= '<option value="">&#8212; ' . __( 'Choose widget to duplicate', self::$textdomain ) . ' &#8212;</option>';
		foreach ( $widgets as $widget ) {
			$label = $wp_registered_widgets[$widget]['name'];
			$title = self::get_widget_title( $widget );
			if ( ! empty( $title ) )
				$label .= ": $title";
			$label .= ' &#8212; ' . self::get_sidebar_name( self::find_sidebar_for_widget( $widget ) ) . '';
			$selected = $value == $widget ? " selected='selected'" : '';
			$html .= '<option value="' . $widget . '" ' . $selected . '">' . $label . "</option>\n";
		}
		$html .= "</select>\n";
		return $html;
	}

	/**
	 * Returns boolean indicating if the specified widget is active.
	 *
	 * @param string $widget_id The id of the widget
	 * @return bool True if the widget is active; false otherwise
	 */
	public static function is_widget_active( $widget_id ) {
		global $wp_registered_widgets;

		if ( ! isset( $wp_registered_widgets[$widget_id] ) )
			return false;

		return is_active_widget( $wp_registered_widgets[$widget_id]['callback'], $widget_id, false, false );
	}

	/**
	 * Returns the id of the sidebar containing the specified widget.
	 *
	 * @param int $widget_id Id of the widget
	 * @return int|null The sidebar id, if one was found to contain widget_id; otherwise null
	 */
	public static function find_sidebar_for_widget( $widget_id ) {
		$sidebars = wp_get_sidebars_widgets(); // NOTE: This is a private method

		foreach ( $sidebars as $sidebar => $widgets ) {
			if ( 'wp_inactive_widgets' == $sidebar )
				continue;

			if ( in_array( $widget_id, $widgets ) )
				return $sidebar;
		}
		return null;
	}

	/**
	 * Returns the name of the specified sidebar.
	 *
	 * @param int $sidebar_id ID of the sidebar
	 * @return string The name of the sidebar
	 */
	public static function get_sidebar_name( $sidebar_id ) {
		global $wp_registered_sidebars;
		if ( isset( self::$_sidebar_names[$sidebar_id] ) )
			$name = self::$_sidebar_names[$sidebar_id];
		elseif ( isset( $wp_registered_sidebars[$sidebar_id] ) )
			$name = self::$_sidebar_names[$sidebar_id] = $wp_registered_sidebars[$sidebar_id]['name'];
		else
			$name = '';
		return $name;
	}

	/**
	 * Deletes a widget.
	 *
	 * @param string $widget_id The id of the widget to delete
	 */
	public static function delete_widget( $widget_id ) {
		global $wp_registered_widgets;
		if ( ! isset( $wp_registered_widgets[$widget_id] ) )
			return false;
		$sidebars   = wp_get_sidebars_widgets();
		$sidebar_id = self::find_sidebar_for_widget( $widget_id );
		$sidebar    = isset( $sidebars[$sidebar_id] ) ? $sidebars[$sidebar_id] : array();
		$sidebar    = array_diff( $sidebar, array( $widget_id ) );
		$sidebars[$sidebar_id] = $sidebar;
		wp_set_sidebars_widgets( $sidebars );
		return true;
	}

	/**
	 * Deletes duplicates whose source widgets have been deactivated or deleted.
	 *
	 * Technically there isn't any harm in leaving lame dupes in place. Their
	 * dropdowns will indicate they need a value set since the source isn't
	 * active. Also, the front-end won't show lame dupes. I think it's
	 * nicer to clean things up though, even at the cost of a little time for
	 * processing.
	 */
	public static function delete_lame_dupes() {
		$duplicates = get_option( 'widget_' . self::$widget_id );
		foreach ( $duplicates as $num => $settings ) {
			if ( isset( $settings[self::$input_name] ) && ! self::is_widget_active( $settings[self::$input_name] ) ) {
				self::delete_widget( self::$widget_id . '-' . $num );
			}
		}
	}

	/**
	 * Outputs a source widget.
	 *
	 * The approach used here abides by the before_title, after_title,
	 * before_widget, and after_widget of the duplicate widget's sidebar
	 * (and therefore not those settings for the source widget)
	 *
	 * @param string $source_widget_id The id of the widget being duplicated
	 * @param string $widget_id The id of the duplicate widget
	 * @param array $args The settings for the target sidebar
	 */
	public static function output_widget( $source_widget_id, $widget_id, $args ) {
		global $wp_registered_widgets;

		if ( ! self::is_widget_active( $source_widget_id ) ) {
			self::delete_widget( $widget_id );
			return;
		}

		$callback = $wp_registered_widgets[$source_widget_id]['callback'];

		$parts = explode( '-', $source_widget_id );
		$num = end( $parts );

		// Add some class information that pertains to the source widget (its
		// id and general type are added as additional classes)
		if ( isset( $args['before_widget'] ) ) {
			$more_class = implode( ' ', array( $source_widget_id, $wp_registered_widgets[$source_widget_id]['classname'] ) );
			$args['before_widget'] = preg_replace( '/(class=[\'\"])/', '${1} ' . $more_class . ' ', $args['before_widget'] );
		}

		if ( is_callable( $callback ) )
			call_user_func_array( $callback, array( $args, $num ) );
	}

	/**
	 * Returns a count of the number of duplicates of the specified widget.
	 *
	 * @param string $widget_id The id of the widget
	 * @return integer The number of duplicates of the widget
	 */
	public static function count_duplicates( $widget_id ) {
		$count = 0;

		$duplicates = get_option( 'widget_' . self::$widget_id );
		foreach ( (array) $duplicates as $num => $settings ) {
			if ( isset( $settings[self::$input_name] ) && $settings[self::$input_name] == $widget_id )
				$count += 1;
		}

		return $count;
	}

	/**
	 * Adds a note to widgets that have duplicates.
	 */
	public static function note_duplicates( $obj, $return, $instance ) {
		// Don't do anything if the widget doesn't have any duplicates
		if ( ( $n = self::count_duplicates( $obj->id ) ) == 0 )
			return;

		echo '<p style="font-style:italic;">';
		echo sprintf( _n(
			'<strong>NOTE:</strong> This widget has <strong>%s</strong> duplicate.',
			'<strong>NOTE:</strong> This widget has <strong>%s</strong> duplicates.',
			$n,
			self::$textdomain ), $n
		);
		echo ' ';
		echo _n(
			'If you deactivate or delete this widget, that duplicate will be deleted.',
			'If you deactivate or delete this widget, those duplicates will be deleted.',
			$n,
			self::$textdomain
		);
		// Output hidden markup that JS will use to insert into the titlebar of duplicated widgets
		echo '<span class="widget_is_duplicated" style="display:none;">[ D(n) ]</span>';
		echo '</p>';
	}


	/* =====================================================================================
	 *
	 * START OF INSTANCE METHODS
	 *
	 * ===================================================================================== */


	/**
	 * Constructor to create widget.
	 */
	public function __construct() {
		self::$title = __( 'Duplicate', self::$textdomain );
		$widget_ops = array(
			'classname'   => 'widget_' . self::$widget_id,
			'description' => __( 'Exact duplicate of any other widget.', self::$textdomain ) . ' ' .
							 __( '(If the source widget gets deactivated or deleted, its duplicate(s) disappear.)', self::$textdomain )
		);
		$control_ops = array( 'width' => '300' );
		parent::__construct( self::$widget_id, self::$title, $widget_ops, $control_ops );
	}

	/**
	 * Outputs the widget on the front-end.
	 *
	 * @param array $args Array of arguments
	 * @param array $instance Array of instance settings
	 */
	public function widget( $args, $instance ) {
		// Show nothing if the widget doesn't have any saved settings
		if ( ! isset( $instance[self::$input_name] ) )
			return;

		do_action( 'c2c_before_duplicate_widget', $instance, $args );

		self::output_widget( $instance[self::$input_name], $this->id, $args );

		do_action( 'c2c_after_duplicate_widget', $instance, $args );
	}

	/**
	 * Validates the widget before changes get saved.
	 *
	 * Namely, this ensures the widget being duplicated is active.
	 *
	 * @param array $new_instance Array of new setting values
	 * @param array $old_instance Array of old setting values
	 * @return array|false If the settings are valid, the array is returned; otherwise false is returned
	 */
	public function update( $new_instance, $old_instance ) {
		$widget_id = $new_instance[self::$input_name];

		if ( ! empty( $old_instance ) && ! self::is_widget_active( $widget_id ) )
			return false;

		// Save the title as the title of the source widget
		$new_instance['title'] = strip_tags( self::get_widget_title( $widget_id ) );

		return $new_instance;
	}

	/**
	 * Outputs configuration form for the widget.
	 *
	 * @param array $instance Array of instance settings
	 */
	public function form( $instance ) {
		$value = ( isset( $instance[self::$input_name] ) ? $instance[self::$input_name] : '' );
		$title = ( empty( $value ) ? '' : esc_attr( $instance['title'] ) );

		echo "<div class='duplicate_widget_form'>";

		do_action( 'c2c_before_duplicate_widget_form', $instance );

		echo '<input type="hidden" id="' . $this->get_field_id( 'title' ) . '"name="' . $this->get_field_name( 'title' ). '" value="' . $title . '" />';
		echo $this->get_widgets_dropdown( null, $value );

		echo '<p class="description">';
		_e( 'Each dropdown item specifies the name/title of the original widget followed by the name of the sidebar containing that widget.' );
		echo '</p>';
		do_action( 'c2c_after_duplicate_widget_form', $instance );

		echo "</div>";
	}
}

c2c_DuplicateWidget::init();

endif;

?>
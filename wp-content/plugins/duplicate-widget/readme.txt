=== Duplicate Widget ===
Contributors: coffee2code
Donate link: http://coffee2code.com/donate
Tags: widget, widgets, sidebar, duplicate, coffee2code
Requires at least: 3.1
Tested up to: 3.3
Stable tag: 1.0.1
Version: 1.0.1

A widget that can act as a duplicate of another widget (for synchronized use in another sidebar)


== Description ==

A widget that can act as a duplicate of another widget (for synchronized use in another sidebar)

Define a widget once, use it in multiple sidebars.  This saves you from having to manually configure each copy of the widget and later having to worry about keeping them in sync should you ever need to make any changes.  Particularly useful for those who define logic in their themes to conditionally include different versions of a sidebar depending on what template is being shown. Depending on use, it is an alternative to plugins that introduce in-widget logic to determine when widgets should be visible (Widget Logic, Section Widget, Conditional Widgets, etc).

Quick overview of what this plugin does:

* Adds a widget called "Duplicate". The widget's only setting is a dropdown listing all active widgets.  The selected widget will be the widget duplicated by the duplicate widget.
* A duplicate widget shows the same title and content as its source widget, even if those values later get changed in the source widget.
* A widget can be duplicated any number of times and can appear multiple times within the same page. (Yes, even within the same sidebar, though why would you do that?)
* A duplicate widget will abide by the configuration of the sidebar it is placed in, not the configuration of the sidebar containing the source widget.  So it uses 'before_widget', 'after_widget', 'before_title', 'after_title' values of its own sidebar.
* Widgets that are duplicated will have "[D]" prepended to their name in the widget titlebar in the admin to denote they have duplicates.  Also, at the bottom of the widget's configuration form (when the widget is expanded), a short blurb also explains that the widget has duplicate(s) and a count of how many duplicates it has.
* If a widget is deactivated or deleted, if it has any duplicates, they get deleted as well.
* The widget id and widget type of the source widget are included as HTML classes in the duplicate widget's markup.

Links: [Plugin Homepage](http://coffee2code.com/wp-plugins/duplicate-widget/) | [Plugin Directory Page](http://wordpress.org/extend/plugins/duplicate-widget/) | [Author Homepage](http://coffee2code.com)


== Installation ==

1. Unzip `duplicate-widget.zip` inside the `/wp-content/plugins/` directory for your site (or install via the built-in WordPress plugin installer)
1. Activate the plugin through the 'Plugins' admin menu in WordPress
1. Use the 'Duplicate' widget to duplicate any other active widget


== Frequently Asked Questions ==

= What happens to the duplicate(s) of a widget when that widget gets deactivated or deleted? =

When a widget gets deactivated (dragged to the "Inactive Widgets" section of the widgets admin page) or deleted, all of its duplicates get deleted.  The plugin provides numerous cues to make you aware of what widgets have duplicates.

= Why can't I see a newly added widget as an option in a duplicate widget's dropdown? =

If a widget is newly activated, any existing active duplicate widgets will not have it listed in their dropdowns immediately. Either a page reload must occur or the duplicate widget must be saved (which causes the widget to be retrieved via AJAX and thus the dropdown is regenerated).

= Can a widget be duplicated into another sidebar on the same page (so it'll appear twice on the page)? =

Yes.


== Screenshots ==

1. A screenshot of the "Widgets" admin page. The "Text: Mini Bio" widget in the Main Sidebar has a duplicate in Footer Area One.


== Filters ==

The plugin exposes four actions for hooking.  Typically, customizations utilizing these hooks would be put into your active theme's functions.php file, or used by another plugin.

= c2c_before_duplicate_widget (action) =

The 'c2c_before_duplicate_widget' hook allows you to output text, or perform some sort of action, just before the output of the duplicate widget.

Arguments:

* $instance (array) : The settings for the widget instance (namely: title and widget_to_duplicate)
* $args (array) : The configuration for the widget and sidebar

Example:

`
// Output an opening <div> before duplicate widget content
add_action( 'c2c_before_duplicate_widget', 'my_c2c_before_duplicate_widget', 10, 2 );
function my_c2c_before_duplicate_widget( $instance, $args ) {
	echo '<div class="a_duplicate_widget">;
}
`

= c2c_after_duplicate_widget (action) =

The 'c2c_after_duplicate_widget' hook allows you to output text, or perform some sort of action, just after the output of the duplicate widget.

Arguments:

* $instance (array) : The settings for the widget instance (namely: title and widget_to_duplicate)
* $args (array) : The configuration for the widget and sidebar

Example:

`
// Output an closing </div> after duplicate widget content
add_action( 'c2c_after_duplicate_widget', 'my_c2c_after_duplicate_widget', 10, 2 );
function my_c2c_after_duplicate_widget( $instance, $args ) {
	echo '</div>;
}
`

= c2c_before_duplicate_widget_form (action) =

The 'c2c_before_duplicate_widget_form' hook allows you to output text, or perform some sort of action, just before the output of the duplicate widget's configuration form (in the WP admin).

Arguments:

* $instance (array) : The settings for the widget instance (namely: title and widget_to_duplicate)

Example:

`
// Display a message just before the duplicate widget settings form
add_action( 'c2c_before_duplicate_widget_form', 'my_c2c_before_duplicate_widget_form' );
function my_c2c_before_duplicate_widget_form( $instance ) {
	echo '<p>Note: this is a note above the widget settings form.</p>';
}
`

= c2c_after_duplicate_widget_form (action) =

The 'c2c_after_duplicate_widget_form' hook allows you to output text, or perform some sort of action, just after the output of the duplicate widget's configuration form (in the WP admin).

Arguments:

* $instance (array) : The settings for the widget instance (namely: title and widget_to_duplicate)

Example:

`
// Display a message just after the duplicate widget settings form
add_action( 'c2c_after_duplicate_widget_form', 'my_c2c_after_duplicate_widget_form' );
function my_c2c_after_duplicate_widget_form( $instance ) {
	echo '<p>Note: this is a note below the widget settings form.</p>';
}
`

== Changelog ==

= 1.0.1 =
* Minor bugfixes
* Add Upgrade Notice section to readme.txt

= 1.0 =
* Initial release


== Upgrade Notice ==

= 1.0.1 =
Bugfix update: Minor bugfixes.
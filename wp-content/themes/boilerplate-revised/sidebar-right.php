<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */
?>
<div id="right-sidebar">
<?php if ( is_active_sidebar( 'right-sidebar-widget-area' ) ) : ?>
	<?php dynamic_sidebar( 'right-sidebar-widget-area' ); ?>
<?php endif; ?>
<p>Sidebar</p>
</div><!--end right-sidebar-->
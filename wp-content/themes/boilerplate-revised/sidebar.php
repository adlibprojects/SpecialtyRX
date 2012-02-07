<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */
?>
<div id="left-sidebar">
<?php if ( is_active_sidebar( 'left-sidebar-widget-area' ) ) : ?>
	<?php dynamic_sidebar( 'left-sidebar-widget-area' ); ?>
<?php endif; ?>
<p>Sidebar</p>
</div><!--end left-sidebar-->
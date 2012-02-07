<?php
/**
 * The Footer widget areas.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */
?>
<div id="footer-sidebar">
<?php if ( is_active_sidebar( 'footer-widget-area' ) ) : ?>
	<?php dynamic_sidebar( 'footer-widget-area' ); ?>
<?php endif; ?>
</div><!--end footer-sidebar -->

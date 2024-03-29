<?php
/**
 * The template for displaying Tag Archive pages.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>
<?php get_sidebar(); ?>
<div id="main-content">

				<h1><?php
					printf( __( 'Tag Archives: %s', 'boilerplate' ), '' . single_tag_title( '', false ) . '' );
				?></h1>

<?php
/* Run the loop for the tag archive to output the posts
 * If you want to overload this in a child theme then include a file
 * called loop-tag.php and that will be used instead.
 */
 get_template_part( 'loop', 'tag' );
?>

</div><!--end main-content-->
<?php get_sidebar('right'); ?>
<?php get_footer(); ?>
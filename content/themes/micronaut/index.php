<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Micronaut
 */

get_header();
?>
	<main id="main" class="site-main">
		<div class="container">
			<h1><?php esc_html_e( 'Posts', 'micronaut' ); ?></h1>
			<?php
			if ( have_posts() ) :

				// Load posts loop.
				while ( have_posts() ) :
					the_post();
					get_template_part( 'template-parts/content' );
				endwhile;
			else :
				get_template_part( 'template-parts/content', 'none' );
			endif;
			?>
		</div>
	</main><!-- .site-main -->
<?php
get_footer();
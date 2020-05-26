<?php
/**
 * The template for displaying the content.
 *
 * @package    WordPress
 * @subpackage Micronaut
 */

$movie_director = get_post_meta( get_the_ID(), 'movie_director', true );
$movie_year     = get_post_meta( get_the_ID(), 'movie_year', true );
?>
<div class="card mb-3">
	<div class="card-body">
		<?php the_title( sprintf( '<h5 class="card-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h5>' ); ?>
		<div class="card-text">
			<?php the_content(); ?>
		</div>
		<div class="card-text">
			<small class="text-muted">
				<?php echo esc_html__( 'Directed by', 'micronaut' ); ?>
				<?php echo esc_html( $movie_director ); ?>
			</small>
			<br />
			<small class="text-muted">
				<?php echo esc_html__( 'Released', 'micronaut' ); ?>
				<?php echo esc_html( $movie_year ); ?>
			</small>
		</div>
	</div>
</div>

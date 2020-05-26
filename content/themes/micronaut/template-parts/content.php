<?php
/**
 * The template for displaying the content.
 *
 * @package    WordPress
 * @subpackage Micronaut
 */

?>
<article class="card mb-3">
	<div class="card-body">
		<?php the_title( sprintf( '<h5 class="card-title"><a href="%s">', esc_url( get_permalink() ) ), '</a></h5>' ); ?>
		<div class="card-text">
			<?php the_content(); ?>
		</div>
	</div>
</article>

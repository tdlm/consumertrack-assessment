<?php
/**
 * The template for displaying the content.
 *
 * @package    WordPress
 * @subpackage Micronaut
 */

?>
<article>
	<div>
		<?php the_title( sprintf( '<h5><a href="%s">', esc_url( get_permalink() ) ), '</a></h5>' ); ?>
		<div>
			<?php the_content(); ?>
		</div>
	</div>
</article>
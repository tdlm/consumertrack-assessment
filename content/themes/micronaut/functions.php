<?php
/**
 * Theme Functions
 *
 * @package    WordPress
 * @subpackage Micronaut
 * @since      0.0.1
 */
function micronaut_setup() {
	// Register custom post types.
	micronaut_register_post_type_movie();

	// Register custom taxonomies.
	micronaut_register_taxonomy_genre();

	// Register custom meta boxes.
	add_action( 'add_meta_boxes', 'micronaut_add_metaboxes' );
	add_action( 'save_post', 'micronaut_movie_metabox_save_cb', 10, 3 );

	// Enqueue assets.
	add_action( 'wp_enqueue_scripts', 'micronaut_enqueue_assets' );
}

add_action( 'init', 'micronaut_setup' );

/**
 * Enqueue assets (JS/CSS).
 */
function micronaut_enqueue_assets() {
    // TODO: Fill these in.
}

/**
 * Register 'Movie' custom post type.
 */
function micronaut_register_post_type_movie() {
	$args = array(
		'capability_type'    => 'post',
		'labels'             => array(
			'name'          => __( 'Movies', 'micronaut' ),
			'singular_name' => __( 'Movie', 'micronaut' ),
		),
		'has_archive'        => true,
		'hierarchical'       => false,
		'public'             => true,
		'publicly_queryable' => true,
		'query_var'          => true,
		'show_in_menu'       => true,
		'show_ui'            => true,
		'supports'           => array(
			'title',
			'editor',
			'excerpt',
		),
	);

	register_post_type( 'movie', $args );
}

/**
 * Register 'Genre' custom taxonomy for 'Movie' custom post type.
 */
function micronaut_register_taxonomy_genre() {
	$args = array(
		'labels'            => array(
			'name'          => __( 'Genres', 'micronaut' ),
			'singular_name' => __( 'Genre', 'micronaut' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'rewrite'           => array(
			'slug' => 'genre',
		),
		'show_admin_column' => true,
	);

	register_taxonomy( 'genre', 'movie', $args );
}

/**
 * Add custom meta boxes to 'Movie' custom post type.
 */
function micronaut_add_metaboxes() {
	add_meta_box(
		'movie-details',
		__( 'Movie Details', 'micronaut' ),
		'micronaut_movie_metabox_cb',
		array( 'movie' ),
		'side'
	);
}

/**
 * Callback for 'Movie' custom meta box inputs.
 */
function micronaut_movie_metabox_cb() {
	$post_id = get_the_ID();

	$movie_year     = get_post_meta( $post_id, 'movie_year', true );
	$movie_director = get_post_meta( $post_id, 'movie_director', true );

	wp_nonce_field( basename( __FILE__ ), 'movie_metabox_nonce' );
	?>
	<div class="inside">
		<label for="movie_year"><?php esc_html_e( 'Year', 'micronaut' ); ?><br />
			<input name="movie_year" id="movie_year" type="text" value="<?php echo esc_attr( $movie_year ); ?>" />
			<p class="howto"><?php esc_html_e( 'The year this movie was made.', 'micronaut' ); ?></p>
		</label>
		<br />
		<label for="movie_director"><?php esc_html_e( 'Director', 'micronaut' ); ?><br />
			<input name="movie_director" id="movie_director" type="text" value="<?php echo esc_attr( $movie_director ); ?>" />
			<p class="howto"><?php esc_html_e( 'The director of this movie.', 'micronaut' ); ?></p>
		</label>
	</div>
	<?php
}

/**
 * Callback for save of 'Movie' custom meta box inputs.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an existing post being updated or not.
 *
 * @return int $post_id Post ID.
 */
function micronaut_movie_metabox_save_cb( $post_id, $post, $update ) {
	$post = filter_input_array(
		INPUT_POST,
		array(
			'movie_metabox_nonce' => FILTER_SANITIZE_STRING,
			'movie_year'          => FILTER_SANITIZE_NUMBER_INT,
			'movie_director'      => FILTER_SANITIZE_STRING,
		)
	);

	if ( ! isset( $post['movie_metabox_nonce'] ) || ! wp_verify_nonce( $post['movie_metabox_nonce'], basename( __FILE__ ) ) ) {
		return $post_id;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return $post_id;
	}

	$movie_year     = isset( $post['movie_year'] ) ? $post['movie_year'] : '';
	$movie_director = isset( $post['movie_director'] ) ? $post['movie_director'] : '';

	update_post_meta( $post_id, 'movie_year', $movie_year );
	update_post_meta( $post_id, 'movie_director', $movie_director );
}
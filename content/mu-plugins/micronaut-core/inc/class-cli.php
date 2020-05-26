<?php
/**
 * @file       class-cli.php
 * CLI class for Micronaut.
 *
 * @package    WordPress
 * @subpackage Micronaut\Core
 */

namespace Micronaut\Core;

use WP_CLI;
use WP_CLI_Command;

/**
 * Class CLI
 *
 * @package Micronaut\Core
 */
class CLI extends WP_CLI_Command {

	/**
	 * Import Movies for 'Movie' custom post type.
	 *
	 * ## OPTIONS
	 * [--file]
	 * : The CSV file to import from the data/ directory.
	 *
	 * ## EXAMPLES
	 *   wp movies import
	 *
	 * @param array $args       Array of arguments.
	 * @param array $args_assoc Array of associative arguments.
	 *
	 * @return mixed
	 */
	public function import( $args, $args_assoc = array() ) {
		$handle       = '';
		$close_handle = false;
		$args_assoc   = wp_parse_args(
			$args_assoc,
			array(
				'file' => '',
			)
		);

		// Enable line endings auto detection.
		@ini_set( 'auto_detect_line_endings', true );  // @codingStandardsIgnoreLine

		$file = sprintf( '%s/%s', dirname( __FILE__ ) . '/data/', $args_assoc['file'] );

		// Open file pointer if $file is not a resource.
		if ( ! is_resource( $file ) ) {
			$handle = fopen( $file, 'rb' ); // @codingStandardsIgnoreLine
			if ( ! $handle ) {
				WP_CLI::error( sprintf( 'Error retrieving %s file', basename( $file ) ) );

				return false;
			}

			$close_handle = true;
		}

		$created = 0;
		$skipped = 0;
		$headers = fgetcsv( $handle );

		while ( ( $row = fgetcsv( $handle ) ) ) {

			list(
				$movie_actors,
				$movie_description,
				$movie_director,
				$movie_genre,
				$movie_rating,
				$movie_name,
				$movie_rank,
				$movie_runtime,
				$movie_votes,
				$movie_year
				) = $row;

			$movie_genres = explode( ', ', $movie_genre );

			$new_post_id = wp_insert_post(
				array(
					'meta_input'   => array(
						'movie_year'     => $movie_year,
						'movie_director' => $movie_director,
					),
					'post_content' => $movie_description,
					'post_excerpt' => $movie_description,
					'post_name'    => sanitize_title( $movie_name ),
					'post_title'   => $movie_name,
					'post_type'    => 'movie',
				)
			);

			wp_set_object_terms( $new_post_id, $movie_genres, 'genre' );
			$id = wp_publish_post( $new_post_id );

			if ( is_wp_error( $id ) ) {
				WP_CLI::warning( $id );
				$skipped ++;
			} else {
				WP_CLI::success(
					sprintf(
						'Created movie "%s" as post %d with genres (%s)',
						$movie_name,
						$new_post_id,
						implode( ', ', $movie_genres )
					)
				);
				$created ++;
			}
		}

		WP_CLI::success( sprintf( 'Created %d movies and skipped %d', $created, $skipped ) );

		// Close open file pointer if we've opened it.
		if ( $close_handle ) {
			fclose( $handle );
		}

		flush_rewrite_rules();
	}

	/**
	 * Delete all Movies for 'Movie' custom post type.
	 *
	 * ## EXAMPLES
	 *   wp movies delete
	 *
	 * @param array $args       Array of arguments.
	 * @param array $args_assoc Array of associative arguments.
	 *
	 * @return mixed
	 */
	public function delete( $args, $args_assoc = array() ) {
		$args_assoc = wp_parse_args(
			$args_assoc,
			array(
				'force' => true,
			)
		);

		$movie_posts = get_posts(
			array(
				'fields'         => 'ids',
				'post_type'      => 'movie',
				'posts_per_page' => '-1',
			)
		);

		foreach ( $movie_posts as $movie_post_id ) {
			$result = wp_delete_post( $movie_post_id, $args_assoc['force'] );

			if ( ! $result ) {
				WP_CLI::warning( sprintf( 'Unable to delete post ID %d: %s', $movie_post_id, $result ) );
			} else {
				WP_CLI::success( sprintf( 'Deleted movie ID %d', $movie_post_id ) );
			}
		}
	}
}

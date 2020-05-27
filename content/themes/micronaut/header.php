<?php
/**
 * The template for displaying the header.
 *
 * @package    WordPress
 * @subpackage Micronaut
 */

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<title><?php wp_title( '', true, '' ); ?></title>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<main id="content" role="main">
	<nav class="navbar navbar-expand-lg navbar-light bg-light container sticky-top">
		<a class="navbar-brand" href="/"><?php echo esc_html__( 'Micronaut', 'micronaut' ); ?></a>
		<ul class="navbar-nav mr-auto">
			<li class="nav-item">
				<a class="nav-link" href="#posts"><?php echo esc_html__( 'Posts', 'micronaut' ); ?></a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#movies"><?php echo esc_html__( 'Movies', 'micronaut' ); ?></a>
			</li>
		</ul>
	</nav>
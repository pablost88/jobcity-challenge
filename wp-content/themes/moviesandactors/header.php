<?php
/**
 * The header.
 *
 * This is the template that displays all of the <head> section and everything up until main.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package moviesandactors
 */

?>
<!doctype html>
<html>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<?php wp_head(); ?>
	</head>

	<body <?php body_class(); ?>>
	<div id="page" class="site">

		<header class="header flex">
			<div class="global-padding">
				<div class="max-width-1 center">
					<h1 class="h1-big">Jobsity Cinema</h1>
				</div>

				<nav class="max-width-1 center">
					<?php
					wp_nav_menu(
						array(
							'theme_location'  => 'primary',
							'menu_class'      => 'menu-wrapper flex',
							'container_class' => 'primary-menu-container',
							'items_wrap'      => '<ul id="primary-menu-list" class="%2$s clean-list">%3$s</ul>',
							'fallback_cb'     => false,
						)
					);
					?>
				</nav>
			</div>
		</header>

		<main id="main" class="site-main max-width-1 global-padding center">

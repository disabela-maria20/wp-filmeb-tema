<!DOCTYPE html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<title>Cine B</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
	<!-- Inicio Wordpress Header -->
	<?php wp_head(); ?>
	<!-- Final Wordpress Header -->
</head>

<body>

	<header class="header">
		<div class="container">
			<nav class="grid-12 header_menu">
				<?php
				$args = array(
					'menu' => 'principal',
					'theme_location' => 'menu-principal',
					'container' => false
				);
				wp_nav_menu($args);
				?>
			</nav>
		</div>
	</header>
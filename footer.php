<footer class="rodape">
	<div class="bg-filmeB">
		<img
			src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-boletim-filme-b.png"
			alt="cine B" />
	</div>
	<div class="container">
		<div class="area_saiba_mais">
			<h2>Conheça o Boletim</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
			<a href="">Saiba Mais</a>
		</div>
	</div>
	<div class="area_links_rodape">
		<div class="container">
			<div class="grid grid-5-lg gap-32">
				<nav>
					<h2>Mapa do site</h2>
					<?php
					$args = array(
						'menu' => 'menu rodape',
						'theme_location' => 'menu-rodape',
						'container' => false
					);
					wp_nav_menu($args); ?>
				</nav>
				<nav>
					<h2>Institucional</h2>
					<?php
					$args = array(
						'menu' => 'institucinal rodape',
						'theme_location' => 'institucinal-rodape',
						'container' => false
					);
					wp_nav_menu($args); ?>
				</nav>
				<nav>
					<h2>Sobre a Filme B</h2>
					<?php
					$args = array(
						'menu' => 'sobre',
						'theme_location' => 'sobre',
						'container' => false
					);
					wp_nav_menu($args); ?>
				</nav>
				<nav class="produtos">
					<h2>Produto Filme B</h2>
					<img
						src="<?php echo get_template_directory_uri(); ?>/assets/images/box-office-brasil.png"
						alt="cine B" />
				</nav>
				<nav class="redes">
					<h2>Redes sociais</h2>
					<ul>
						<li>
							<a href="">
								<i class="bi bi-instagram"></i>
								<span>Instagram</span>
							</a>
						</li>
						<li>
							<a href="">
								<i class="bi bi-twitter"></i>
								<span>Twitter</span>
							</a>
						</li>
						<li>
							<a href="">
								<i class="bi bi-facebook"></i>
								<span>Facebook</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</div>
	</div>
	</div>
	<p>1997 / 2024 © Filme B - Todos os direitos reservados - Criado por Kamus</p>
</footer>

<?php wp_footer(); ?>
</body>

</html>
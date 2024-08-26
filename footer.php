<footer class="rodape">
	<div class="bg-filmeB">
		<img
        src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-boletim-filme-b.png"
        alt="cine B"
    	/>
	</div>
	<div class="container">
		<div class="area_saiba_mais">
			<h2>Conheça o Boletim</h2>
			<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.</p>
			<a href="">Saiba Mais</a>
		</div>
		<div class="area_links_rodape">
			<nav>
				<h2>Mapa do site</h2>
				<?php 
          $args = array(
						'menu' =>'principal', 
            'theme_location' => 'menu-principal', 
            'container' =>false); 
            wp_nav_menu($args); ?>
			</nav>
			<nav>
				<h2>Institucional</h2>
				<?php 
          $args = array(
						'menu' =>'principal', 
            'theme_location' => 'menu-principal', 
            'container' =>false); 
            wp_nav_menu($args); ?>
			</nav>
			<nav>
				<h2>Sobre a Filme B</h2>
				<?php 
          $args = array(
						'menu' =>'principal', 
            'theme_location' => 'menu-principal', 
            'container' =>false); 
            wp_nav_menu($args); ?>
			</nav>
			<nav>
				<h2>Produto Filme B</h2>
				<?php 
          $args = array(
						'menu' =>'principal', 
            'theme_location' => 'menu-principal', 
            'container' =>false); 
            wp_nav_menu($args); ?>
			</nav>
			<nav>
				<h2>Redes sociais</h2>
				<?php 
          $args = array(
						'menu' =>'principal', 
            'theme_location' => 'menu-principal', 
            'container' =>false); 
            wp_nav_menu($args); ?>
			</nav>
		</div>
	</div>
</footer>
<?php wp_footer(); ?>
</body>

</html>
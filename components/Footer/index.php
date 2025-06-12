<footer class="rodape">
  <section class="home_newllater">
    <div class="container">
      <div class="grid">
        <div>
          <h2>Receba a nossa newsletter</h2>
          <p>
            Fique por dentro do que movimenta o mercado de cinema! Receba tendências, estreias, bilheterias e os temas
            mais relevantes da indústria diretamente no seu e-mail.</p>
        </div>
        <?php echo do_shortcode('[newsletter_form]'); ?>
      </div>
    </div>
  </section>
  <div class="area_links_rodape">
    <div class="container">
      <div class="grid gap-32">
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
          <h2>Produtos Filme B</h2>
          <a href="http://www.filmebboxofficebrasil.com.br/Login" target="_blank" rel="noopener noreferrer">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/box-office-brasil.png" alt="cine B" />
          </a>
        </nav>
        <div></div>
        <nav class="redes">
          <h2>Redes sociais</h2>
          <ul>
            <li>
              <a href="https://www.instagram.com/filmebportal/#" target="_blank">
                <i class="bi bi-instagram"></i>
                <span>Instagram</span>
              </a>
            </li>

            <li>
              <a href="https://www.facebook.com/FilmeB" target="_blank">
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
  <p>1997 / 2024 © Filme B - Todos os direitos reservados - Criado por Vibezz</p>
</footer>
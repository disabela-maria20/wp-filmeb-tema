<?php
// Template Name: Assine
get_header();
?>
<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>
<main class="page-assine">
  <section class="bg1"
    style="background: linear-gradient(180deg, #ffffff00, #221115), url('<?php echo get_template_directory_uri(); ?>/assets/images/banner/bg-hero-pagina-de-vendas-1024x450.jpg');">
    <div class="title">
      <div class="container">
        <div>
          <h2>Assine os conteúdos exclusivos da Filme B </h2>
          <h1>Dados e análises para a tomada de decisão do seu negócio.</h1>
        </div>
      </div>
    </div>
  </section>
  <section class="bg2">
    <div class="container">
      <div class="grid grid-2-md gap-32">
        <div>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/thumb-cinema-filmeb.png" alt="">
        </div>
        <div>
          <h2>Boletim Filme B: o fim de semana cinematográfico em números e análises</h2>
          <p>Por meio de um conteúdo especializado e de referência, o Boletim Filme B, há mais de 25 anos no mercado,
            oferece semanalmente para seus assinantes, sempre às segundas e terças-feiras, o ranking das maiores
            bilheterias do fim de semana no Brasil e no mundo.</p>
          <p>Instrumento de inteligência de mercado, o Boletim Filme B traz um panorama completo da evolução do setor
            cinematográfico.
          </p>
          <h2>Seções do Boletim</h2>
          <ul>
            <li>Ranking do fim de semana cinematográfico no Brasil (maiores bilheterias);</li>
            <li>Ranking do fim de semana EUA;</li>
            <li>Ranking das 10 maiores bilheterias do ano;</li>
            <li>Análise do fim de semana cinematográfico (no Brasil e no mundo);</li>
            <li>Opinião;</li>
            <li>Rapidinhas;</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <section class="bg3">
    <div class="container">
      <div class="grid grid-2-md gap-32">
        <div>
          <h2>Notícias: cobertura diária do mercado</h2>
          <p>O assinante do portal tem acesso ainda a notícias exclusivas, produzidas por uma equipe de especialistas,
            sobre o mercado de cinema/audiovisual.</p>
          <h2>Alguns temas:</h2>
          <ul>
            <li>Políticas públicas do cinema/audiovisual;</li>
            <li>Streaming;</li>
            <li>Festivais;</li>
            <li>Eventos de mercado;</li>
            <li>Line-ups de distribuidoras;</li>
            <li>Produção de filmes;</li>
            <li>Mercado exibidor;</li>
            <li>E muito mais.</li>
          </ul>
        </div>
        <div>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/thumb-noticias-filmeb.png" alt="">
        </div>
      </div>
    </div>
  </section>
  <!-- <section class="bg4">
    <div class="container">
      <div class="grid grid-2-md gap-32">
        <div>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/thumb-database-filmeb.png" alt="">
        </div>
        <div>
          <h2>Database do mercado de cinema no Brasil</h2>
          <p>Único banco de dados disponibilizado para o mercado. Números e comparativos dos setores de exibição,
            produção
            e distribuição no Brasil desde o ano 2000.</p>
          <ul>
            <li>Público total dos filmes;</li>
            <li>Renda total dos filmes;</li>
            <li>Preço médio do ingresso no ano;</li>
            <li>Número total de cinemas e salas no ano <em>(cinemas e salas abertas e fechadas)</em>;</li>
            <li>Total de filmes lançados no ano <em>(nacionais e internacionais)</em>;</li>
            <li>Líderes do ano <em>(filmes, distribuidores, exibidores)</em>;</li>
            <li>E muito mais.</li>
          </ul>
        </div>
      </div>
    </div>
  </section> -->
  <footer class="footer-assine">
    <div class="container">
      <div class="area">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-boletim-alt@2x.1c82f3.png"
          alt="Boletim FilmeB" width="174" height="65" class="mb-4">
        <h2 class="">
          Clique e assine o Boletim Filme B
        </h2>
        <p>
          Lorem ipsum dolor sit amet, consectetur adipiscing elit.Ut elit tellus, luctus nec ullamcorper mattis,
          pulvinar
          dapibus leo.
        </p>
        <p>
          R$ 700,00/ano
        </p>
        <div class="btn-center">
          <a href="#" id="assinar-filmeb" data-product-id="106">
            Assine o FilmeB
          </a>
        </div>
      </div>
    </div>
    </div>
</main>
<footer class="rodape">
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
          <h2>Produto Filme B</h2>
          <a href="<?php echo get_site_url(); ?>/box-office/">
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
<?php get_footer(); ?>

<script>
document.getElementById('assinar-filmeb').addEventListener('click', function(e) {
  e.preventDefault();

  const productId = this.getAttribute('data-product-id');

  fetch(`<?php echo admin_url('admin-ajax.php'); ?>?action=add_to_cart_ajax&product_id=${productId}`)
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        window.location.href = "<?php echo get_site_url(); ?>/finalizar-compra/";
      } else {
        alert('Erro ao adicionar ao carrinho.');
      }
    });
});
</script>
<?php
// Template Name: Quem somos
get_header();
?>

<div class="w-full p-35 img-banner bannerMobile">
  <?php echo do_shortcode('[bm_banner id="399779"]');?>
</div>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <?php echo do_shortcode('[bm_banner id="399761"]');?>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <?php echo do_shortcode('[bm_banner id="399761"]');?>
    </div>
  </div>
</section>

<section class="padrao page-quemsomos">
  <div class="container">
    <div class="grid grid-2-md gap-22">
      <div>
        <h1>Quem somos</h1>
        <h2 class="sub">A Empresa</h2>
        <p class="mb40">Fundada em 1997, a Filme B nasceu para reunir, analisar e traduzir em conhecimento tudo aquilo
          que movimenta
          o mercado cinematográfico brasileiro. Ao longo de quase três décadas, tornou-se referência nacional em dados
          de
          bilheteria, tendências de exibição, políticas públicas e inteligência de negócios para distribuidores,
          exibidores, produtores, instituições públicas e privadas, e imprensa especializada.</p>
        <h2 class="sub">Sobre o portal Filme B</h2>
        <p>O portal Filme B oferece uma visão panorâmica do setor, permitindo que distribuidores,
          exibidores,
          produtores, investidores e pesquisadores enxerguem, em detalhes, como cada filme performa e como o mercado
          brasileiro se
          movimenta.</p>
      </div>
      <div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner-quem-somos.png"
          alt="Banner Quem Somos" class="img-fluid">
      </div>
    </div>

    <div class="area">
      <h2>Conheça e <button class="btn-assine assinar-filmeb" data-product-id="106">Assine</button> os
        nossos produtos:</h2>
    </div>

    <div class="grid grid-2-md gap-22">
      <div class="card-assine">
        <div class="flex">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon/estatisticas.png"
            alt="Ícone Estatísticas">
          <h3>Boletim Filme B</h3>
        </div>
        <h4>Com publicação semanal, oferece ao assinante:</h4>
        <ul>
          <li>As maiores bilheterias do fim de semana no país</li>
          <li><strong>Top 10 EUA:</strong> panorama do mercado norte-americano</li>
          <li>As 10 maiores bilheterias do ano até o momento</li>
          <li>Análise dos resultados - Brasil e mundo</li>
          <li><strong>Opinião:</strong> comentários exclusivos de especialistas</li>
          <li><strong>Rapidinhas:</strong> flashes de informações do setor</li>
        </ul>
      </div>
      <div class="card-assine">
        <div class="flex">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon/boletim-de-noticias.png"
            alt="Ícone Boletim de Notícias">
          <h3>Notícias Exclusivas</h3>
        </div>
        <h4>Com publicações diárias, oferece ao assinante:</h4>
        <ul>
          <li>Tendências e indicadores do mercado cinematográfico</li>
          <li>Line-ups das distribuidoras e estreias da semana</li>
          <li>Políticas públicas e leis de incentivo ao audiovisual brasileiro</li>
          <li>Festivais e eventos nacionais e internacionais</li>
          <li>Mercados de streaming e televisão</li>
        </ul>
      </div>
    </div>

    <div class="mx-485 ">
      <div class="card-assine">
        <div class="flex">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon/boletim-de-noticias.png"
            alt="Ícone Rapidinhas">
          <h3>Rapidinhas</h3>
        </div>
        <h4>Pílulas informativas:</h4>
        <p>Mudanças de data, contratações, aquisições, bastidores e curiosidades que afetam o dia a dia do setor.</p>
      </div>
    </div>

    <div class="area">
      <h2>Conteúdos de livre acesso:</h2>
    </div>
    <div class="grid grid-2-md gap-22 bg-gray">
      <div class="card-assine">
        <div class="flex">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon/estrela.png"
            alt="Ícone Estatísticas">
          <h3>Calendário de Lançamentos</h3>
        </div>
        <p>Calendário permanentemente atualizado dos próximos lançamentos cinematográficos, com diferentes filtros,
          oferecendo visão rápida para planejamento de distribuição e programação de salas.</p>
      </div>
      <div class="card-assine">
        <div class="flex">
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon/boletim-de-noticias.png"
            alt="Ícone Boletim de Notícias">
          <h3>Notícias Exclusivas</h3>
        </div>
        <p>Cobertura diária das principais movimentações - exibição, leis de incentivo, tecnologia, regulamentação,
          entre outros assuntos de interesse do setor - no Brasil e no mundo.</p>
      </div>
    </div>
  </div>
  <section class="area-box">
    <div class="grid grid-2-md gap-22">
      <div>
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon/box-office-brasil.png"
          alt="Ícone Boletim de Notícias">
      </div>
      <div>
        <h2>Conheça também</h2>
        <p>Banco de dados atualizado diariamente que reúne
          bilheterias de todos os filmes exibidos no país, com
          detalhes por público, renda, regiões e cinemas.</p>
        <div>
          <button class="btn-assine assinar-filmeb" data-product-id="106"><span>Assine</span></button>
        </div>
      </div>
    </div>
  </section>

</section>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
  // Verifica se os botões existem
  var assinarButtons = document.querySelectorAll('.assinar-filmeb');

  // Splide initialization (se necessário)
  if (document.getElementById('datas')) {
    var splide = new Splide('#datas', {
      arrows: true,
      pagination: false,
    });
    splide.mount();
  }

  assinarButtons.forEach(function(button) {
    button.addEventListener('click', function(e) {
      e.preventDefault();

      const productId = this.getAttribute('data-product-id');
      const ajaxUrl = '<?php echo admin_url("admin-ajax.php"); ?>';

      button.disabled = true;
      button.textContent = 'Processando...';

      fetch(`${ajaxUrl}?action=add_to_cart_ajax&product_id=${productId}`, {
          method: 'GET',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
        })
        .then(response => {
          if (!response.ok) {
            throw new Error('Erro na rede: ' + response.statusText);
          }
          return response.json();
        })
        .then(data => {
          if (data.success) {
            window.location.href = "<?php echo get_site_url(); ?>/finalizar-compra/";
          } else {
            console.error('Erro no servidor:', data.message || 'Erro desconhecido');
            alert('Erro ao adicionar ao carrinho: ' + (data.message || 'Erro desconhecido'));
            button.disabled = false;
            button.textContent = 'ASSINE';
          }
        })
        .catch(error => {
          console.error('Erro no fetch:', error);
          alert('Ocorreu um erro ao processar sua solicitação: ' + error.message);
          button.disabled = false;
          button.textContent = 'ASSINE';
        });
    });
  });
});
</script>
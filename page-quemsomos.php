<?php
// Template Name: Quem somos
get_header();
?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="padrao page-vendas">
  <div class="container">
    <h1>QUEM SOMOS</h1>

    <h2>A empresa</h2>
    <p>Fundada em 1997, a Filme B nasceu para reunir, analisar e traduzir em conhecimento tudo aquilo que movimenta o
      mercado cinematográfico brasileiro. Ao longo de quase três décadas, tornou-se referência nacional em dados de
      bilheteria, tendências de exibição, políticas públicas e inteligência de negócios para distribuidores, exibidores,
      produtores, instituições públicas e privadas, e imprensa especializada.</p>

    <h2>Sobre o portal Filme B</h2>
    <p>O portal Filme B oferece uma visão panorâmica do setor, permitindo que distribuidores, exibidores, produtores,
      investidores e pesquisadores enxerguem, em detalhes, como cada filme performa e como o mercado brasileiro se
      movimenta.</p>

    <h2>Conteúdo exclusivo para assinantes</h2>
    <br>
    <br>
    <button class="btn-assine assinar-filmeb" data-product-id="106">ASSINE</button>
    <br>
    <br>
    <br>
    <h3>Boletim Filme B</h3>
    <p>Com publicação semanal, oferece ao assinante:</p>
    <ul>
      <li>As maiores bilheterias do fim de semana no país</li>
      <li>Top 10 EUA: panorama do mercado norte-americano</li>
      <li>As 10 maiores bilheterias do ano até o momento</li>
      <li>Análises dos resultados - Brasil e mundo</li>
      <li>Opinião: comentários exclusivos de especialistas</li>
      <li>Rapidinhas: flashes de informações do setor</li>
    </ul>

    <h3>Notícias exclusivas</h3>
    <p>Com publicações diárias, oferece ao assinante:</p>
    <ul>
      <li>Tendências e indicadores do mercado cinematográfico</li>
      <li>Line-ups das distribuidoras e estreias da semana</li>
      <li>Políticas públicas e leis de incentivo ao audiovisual brasileiro</li>
      <li>Festivais e eventos nacionais e internacionais</li>
      <li>Mercados de streaming e televisão</li>
    </ul>

    <h3>Rapidinhas</h3>
    <p>Pílulas informativas: mudanças de data, contratações, aquisições, bastidores e curiosidades que afetam o dia
      a
      dia do setor.</p>

    <h2>Conteúdos de livre acesso</h2>

    <h3>Lançamentos</h3>
    <p>Calendário permanentemente atualizado dos próximos lançamentos cinematográficos, com diferentes filtros,
      oferecendo
      visão rápida para planejamento de distribuição e programação de salas.</p>

    <h3>Notícias</h3>
    <p>Cobertura diária das principais movimentações - exibição, leis de incentivo, tecnologia, regulamentação, entre
      outros assuntos de interesse do setor - no Brasil e no mundo.</p>

    <button class="btn-assine assinar-filmeb" data-product-id="106 ">ASSINE</button>
  </div>
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
            alert('Erro ao adicionar ao carrinho: ' + (data.message || ''));
            button.disabled = false;
            button.textContent = 'ASSINE';
          }
        })
        .catch(error => {
          console.error('Erro no fetch:', error); // Mostra erros no console
          alert('Ocorreu um erro ao processar sua solicitação: ' + error.message);
          button.disabled = false;
          button.textContent = 'ASSINE';
        });
    });
  });
});
</script>
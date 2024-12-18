<?php
get_header();
?>

<?php
$current_page_slug = basename(get_permalink());
$category_slug = str_replace('boletim/', '', $current_page_slug);
$banner_id = "185";
$author_id = get_the_author_meta('ID');

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);

$query = new WP_Query($args);



$termos = get_terms(array(
  'taxonomy'   => 'generos',
  'hide_empty' => false, 
));

$tecnologias = get_terms(array(
  'taxonomy'   => 'tecnologias',
  'hide_empty' => false, 
));

$distribuidoras = get_terms(array(
  'taxonomy'   => 'distribuidoras',
  'hide_empty' => false, 
));

$paises = get_terms(array(
  'taxonomy'   => 'paises',
  'hide_empty' => false, 
));

$meses = [
  1 => 'Janeiro',
  2 => 'Fevereiro',
  3 => 'Março',
  4 => 'Abril',
  5 => 'Maio',
  6 => 'Junho',
  7 => 'Julho',
  8 => 'Agosto',
  9 => 'Setembro',
  10 => 'Outubro',
  11 => 'Novembro',
  12 => 'Dezembro',
];

$args = array(
  'post_type' => 'filmes', 
  'posts_per_page' => -1, 
  'post_status' => 'publish' 
);
$filmes = new WP_Query($args);

function render_terms($field_key) {
  $distribuicao = CFS()->get($field_key);
  $output = '';
  if (!empty($distribuicao)) {
      foreach ($distribuicao as $term_id) {
          $term = get_term($term_id);
          if ($term && !is_wp_error($term)) {
              $output .= '<div>' . esc_html($term->name) . '</div>';
          }
      }
  }

  return $output;
}

if ($query->have_posts()) :
  while ($query->have_posts()) : $query->the_post();

    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $full_banner = CFS()->get('full_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
    $super_banner = CFS()->get('super_banner', $banner_id);

?>
<img src="<?php echo esc_url($banner_superior); ?>" class="img-banner bannerMobile" alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner" alt="banner">
    <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
  </div>
</div>

<?php
  endwhile;
  wp_reset_postdata();
endif;
?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner bannerDesktop" alt="banner">
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </div>
  </div>
</section>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="container page-filmes">
  <h1>Cine-semana</h1>

  <div class="grid-filtros-config">
    <div class="ordem">
      <button aria-label="ordem 1"><i class="bi bi-border-all"></i></button>
      <button aria-label="ordem 2"><i class="bi bi-grid-1x2"></i></button>
      <button aria-label="imprimir" onClick="window.print()"><i class="bi bi-printer"></i></button>
    </div>
    <section id="datas" class="splide">
      <div class="splide__track">
        <ul class="splide__list">
          <li class="splide__slide">
            Quinta-feira, 13/06/2024
          </li>
          <li class="splide__slide">
            Quinta-feira, 13/06/2024
          </li>
          <li class="splide__slide">
            Quinta-feira, 13/06/2024
          </li>
        </ul>
      </div>
    </section>
    <div class="lancamento">
      <button id="distribuidora">Ver lançamentos por distribuidora</button>
    </div>
  </div>
  <section class="grid-select">
    <form id="formFiltros" method="get" class="grid grid-7-xl gap-22 select-itens">
      <select name="ano" id="ano">
        <option value="">Ano</option>
        <?php foreach ($anos_unicos as $ano) { ?>
        <option value="<?php echo esc_html($ano); ?>"><?php echo esc_html($ano); ?></option>
        <?php } ?>
      </select>
      <select name="mes" id="mes">
        <option value="">Mês</option>
        <?php foreach ($meses  as $mese) {?>
        <option value="<?php echo esc_html($mese); ?>"><?php echo $mese . PHP_EOL;?>
        </option>
        <?php } ?>
      </select>
      <select name="origem" id="origem">
        <option value="">Origem</option>
        <?php foreach ($paises as $paise) {?>
        <option value="<?php echo esc_html($paise->name); ?>"><?php echo $paise->name . PHP_EOL;?>
        </option>
        <?php } ?>
      </select>
      <select name="distribuidor" id="distribuidor">
        <option value="">Distribuidor</option>
        <?php foreach ($distribuidoras as $distribuidora) {?>
        <option value="<?php echo esc_html($distribuidora->name); ?>"><?php echo $distribuidora->name . PHP_EOL;?>
        </option>
        <?php } ?>
      </select>
      <select name="genero" id="genero">
        <option value="">Gênero</option>
        <?php foreach ($termos as $termo) {?>
        <option value="<?php echo esc_html($termo->name); ?>"><?php echo $termo->name . PHP_EOL;?></option>
        <?php } ?>
      </select>
      <select name="tecnologia" id="tecnologia">
        <option value="">Tecnologia</option>
        <?php foreach ($tecnologias as $tecnologia) {?>
        <option value="<?php echo esc_html($tecnologia->name); ?>"><?php echo $tecnologia->name . PHP_EOL;?></option>
        <?php } ?>
      </select>
      <button>Filtrar</button>
    </form>
  </section>
  <section class="area-filmes">
    <div class="lista-filmes" id="lista">
      <h2>
        <i class="bi bi-calendar-check-fill"></i>
        <?php formatar_data_estreia('estreia', true)?>
      </h2>
      <div class="grid-filmes">
        <?php if ($filmes->have_posts()) : ?>
        <?php while ($filmes->have_posts()) : $filmes->the_post(); ?>
        <a class="card" href="<?php echo esc_url(get_post_meta(get_the_ID(), 'link', true));?>">

          <?php $filme_indisponivel = CFS()->get('cartaz');?>
          <?php if($filme_indisponivel === "") {?>
          <h3><?php the_title(); ?></h3>
          <p class="indisponivel">Poster não disponível</p>
          <?php }?>
          <img src="<?php echo CFS()->get('cartaz'); ?>" alt="<?php the_title(); ?>" class="poster">

          <div class="info">
            <ul>
              <li> <span>Título:</span> <strong><?php the_title(); ?></strong> </li>
              <li><span>Distribuição:</span> <strong> <?php echo render_terms('distribuicao')?></strong></li>
              <li> <span>Direção:</span> <strong> <?php echo CFS()->get('direcao'); ?></strong></li>
              <li> <span>País:</span> <strong> <?php echo render_terms('paises')?></strong></li>
              <li> <span>Gênero:</span> <strong> <?php echo render_terms('generos')?></strong></li>
            </ul>
          </div>
        </a>
        <?php endwhile; ?>
        <?php else : ?>
        <p>Sem filmes disponíveis.</p>
        <?php endif; ?>
      </div>
    </div>
    <div class="tabela-filme" id="tabela">
      <h2> <i class="bi bi-calendar-check-fill"></i> Quinta-feira, 13/06/2024</h2>
      <table>
        <thead>
          <tr>
            <th>Título</th>
            <th>Distribuição</th>
            <th>Direção</th>
            <th>País</th>
            <th>Gênero</th>
            <th>Duração</th>
            <th>Elenco</th>
            <th>Classificação</th>
          </tr>
        </thead>
        <tbody>
          <?php if ($filmes->have_posts()) : ?>
          <?php while ($filmes->have_posts()) : $filmes->the_post(); ?>
          <tr>
            <td class="titulo">
              <?php the_title(); ?>
              <span>The watchers</span>
            </td>
            <td><?php echo render_terms('distribuicao')?></td>
            <td><?php echo CFS()->get('direcao'); ?></td>
            <td><?php echo render_terms('paises')?></td>
            <td><?php echo render_terms('generos')?></td>
            <td><?php echo CFS()->get('duracao_minutos'); ?>min</td>
            <td><?php echo CFS()->get('elenco'); ?></td>
            <td><?php echo render_terms('classificacao'); ?></td>
          </tr>
          <?php endwhile; ?>
          <?php else : ?>
          <p>Sem filmes disponíveis.</p>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
    <div class="tabela-distribuidora" id="tableDistribuidora">
      <table>
        <thead>
          <tr>
            <th>Estreia</th>
            <th>Disney</th>
            <th>Paramount</th>
            <th>Sony</th>
            <th>Universal</th>
            <th>Warner</th>
            <th>Diamond</th>
            <th>
              <div>downtown</div>
              <div>/ Paris</div>
            </th>
            <th>Imagem</th>
            <th>Paris</th>
            <th>
              <div>Outras</div>
              <div>Distribuidoras</div>
            </th>
          </tr>

        </thead>
        <tbody>
          <tr>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
            <td>a</td>
          </tr>
        </tbody>
      </table>
    </div>
  </section>

</div>

<?php endwhile;
endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<script>
const cards = document.querySelectorAll('.card');

cards.forEach(card => {
  const cardInfo = card.querySelector('.info');
  card.addEventListener('mousemove', (e) => {
    const rect = card.getBoundingClientRect();
    const mouseX = ((e.clientX - rect.left) / rect.width) * 100;
    const mouseY = ((e.clientY - rect.top) / rect.height) * 100;

    cardInfo.style.position = 'absolute';
    cardInfo.style.left = `${mouseX}%`;
    cardInfo.style.top = `${mouseY}%`;
  });
});


const ordem1Button = document.querySelector('button[aria-label="ordem 1"]');
const ordem2Button = document.querySelector('button[aria-label="ordem 2"]');
const distribuidora = document.querySelector('#distribuidora');

const listaDiv = document.getElementById('lista');
const tabelaDiv = document.getElementById('tabela');
const tableDistribuidora = document.getElementById('tableDistribuidora');

function toggleView(view) {
  if (view === 'lista') {
    listaDiv.style.display = 'block';
    tabelaDiv.style.display = 'none';
    tableDistribuidora.style.display = 'none'
  } else if (view === 'tabela') {
    listaDiv.style.display = 'none';
    tabelaDiv.style.display = 'block';
    tableDistribuidora.style.display = 'none'
  } else if (view === 'tableDistribuidora') {
    listaDiv.style.display = 'none';
    tabelaDiv.style.display = 'none';
    tableDistribuidora.style.display = 'block'
  }
}

ordem1Button.addEventListener('click', () => toggleView('lista'));
ordem2Button.addEventListener('click', () => toggleView('tabela'));
distribuidora.addEventListener('click', () => toggleView('tableDistribuidora'));
toggleView('lista');

document.getElementById('formFiltros').addEventListener('submit', function(e) {
  e.preventDefault();

  // Coletar os valores dos filtros
  const mes = document.getElementById('mes').value;
  const distribuidora = document.getElementById('distribuidora').value;
  const genero = document.getElementById('genero').value;

  // Enviar requisição AJAX
  fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `action=filtrar_filmes&mes=${mes}&distribuidora=${distribuidora}&genero=${genero}`
    })
    .then(response => response.text())
    .then(data => {
      document.getElementById('card-filmes').innerHTML = data;
      document.getElementById('tabela-filme').innerHTML = data;
    });
});
</script>
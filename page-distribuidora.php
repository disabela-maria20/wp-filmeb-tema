<?php // Template Name: Distribuidora
get_header();

// Configuração da consulta com paginação do WordPress
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$posts_per_page = 32;

$args = array(
  'post_type' => 'filmes',
  'posts_per_page' => $posts_per_page,
  'paged' => $paged,
  'meta_key' => 'estreia',
  'orderby'   => array(
    'date' => 'DESC',
    'menu_order' => 'ASC',
    /*Other params*/
  )
);

// Add filter conditions to args before creating the query
if (isset($_GET['ano']) && !empty($_GET['ano'])) {
  $args['meta_query'][] = array(
    'key' => 'estreia',
    'value' => sanitize_text_field($_GET['ano']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['mes']) && !empty($_GET['mes'])) {
  $args['meta_query'][] = array(
    'key' => 'estreia',
    'value' => sanitize_text_field($_GET['mes']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['origem']) && !empty($_GET['origem'])) {
  $args['tax_query'][] = array(
    'key' => 'paises',
    'value' => sanitize_text_field($_GET['origem']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['distribuicao']) && !empty($_GET['distribuicao'])) {
  $args['tax_query'][] = array(
    'key' => 'distribuicao',
    'value' => sanitize_text_field($_GET['distribuicao']),
    'compare' => '=',
  );
}

if (isset($_GET['genero']) && !empty($_GET['genero'])) {
  $args['tax_query'][] = array(
    'key' => 'generos',
    'value' => sanitize_text_field($_GET['genero']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['tecnologia']) && !empty($_GET['tecnologia'])) {
  $args['tax_query'][] = array(
    'taxonomy' => 'tecnologias',
    'field' => 'term_id',
    'terms' => sanitize_text_field($_GET['tecnologia']),
  );
}

if (!empty($args['tax_query']) && count($args['tax_query']) > 1) {
  $args['tax_query']['relation'] = 'AND';
}

if (!empty($args['meta_query']) && count($args['meta_query']) > 1) {
  $args['meta_query']['relation'] = 'AND';
}

// Create the query here, before trying to use it
$filmes_query = new WP_Query($args);

// Processar os dados como na função original
$resultData = array();

$termos = get_terms(array(
  'taxonomy' => 'generos',
  'hide_empty' => false,
));

$tecnologias = get_terms(array(
  'taxonomy' => 'tecnologias',
  'hide_empty' => false,
));

$distribuidoras = get_terms(array(
  'taxonomy' => 'distribuidoras',
  'hide_empty' => false,
));

$paises = get_terms(array(
  'taxonomy' => 'paises',
  'hide_empty' => false,
));

$meses = [
  '01' => 'Janeiro',
  '02' => 'Fevereiro',
  '03' => 'Março',
  '04' => 'Abril',
  '05' => 'Maio',
  '06' => 'Junho',
  '07' => 'Julho',
  '08' => 'Agosto',
  '09' => 'Setembro',
  '10' => 'Outubro',
  '11' => 'Novembro',
  '12' => 'Dezembro',
];

function obter_anos_dos_filmes()
{
  $query = array(
    'post_type' => 'filmes',
    'posts_per_page' => -1,
    'fields' => 'ids'
  );

  $loop = new WP_Query($query);
  $posts = $loop->posts;

  $anos_filmes = [];

  foreach ($posts as $post_id) {
    $data_estreia = get_post_meta($post_id, 'estreia', true);

    if (!empty($data_estreia)) {
      $ano = date('Y', strtotime($data_estreia));

      if (!in_array($ano, $anos_filmes)) {
        $anos_filmes[] = $ano;
      }
    }
  }

  rsort($anos_filmes);

  return $anos_filmes;
}

$anos = obter_anos_dos_filmes();

if ($filmes_query->have_posts()) {
  while ($filmes_query->have_posts()) {
    $filmes_query->the_post();

    $filme = filme_scheme(get_post());
    $estreia = $filme->estreia ?? null;

    if (!$estreia) {
      continue;
    }

    try {
      $dataEstreia = new DateTime($estreia);
      $ano = $dataEstreia->format('Y');
      $mes = $dataEstreia->format('F');
    } catch (Exception $e) {
      continue;
    }

    // Todas as distribuidoras definidas na API
    $distribuidoresBase = [
      'Disney' => [],
      'Paramount' => [],
      'Sony' => [],
      'Universal' => [],
      'Warner' => [],
      'downtownParis' => [],
      'Imagem' => [],
      'Paris' => [],
      'Diamond' => [],
      'OutrasDistribuidoras' => []
    ];

    // Mapeamento de distribuidoras
    $distribuidora = $filme->distribuidoras[0] ?? 'OutrasDistribuidoras';
    $distribuidorasMap = [
      'Diamond/Galeria' => 'Diamond',
      'Disney' => 'Disney',
      'Paramount' => 'Paramount',
      'Sony' => 'Sony',
      'Universal' => 'Universal',
      'Warner' => 'Warner',
      'downtownParis' => 'downtownParis',
      'Paris' => 'Paris'
    ];
    $distribuidora = $distribuidorasMap[$distribuidora] ?? 'OutrasDistribuidoras';

    // Dados do filme
    $filmeData = [
      'link' => $filme->link ?? '',
      'title' => $filme->title ?? '',
      'titulo_original' => $filme->titulo_original ?? ''
    ];

    // Verificar se já existe um item com a mesma data de estreia
    $found = false;
    foreach ($resultData as &$dataItem) {
      if ($dataItem['estreia'] === $estreia) {
        $dataItem[$distribuidora][] = $filmeData;
        $found = true;
        break;
      }
    }

    // Criar novo item se não existir
    if (!$found) {
      $resultData[] = array_merge([
        'estreia' => $estreia,
        'ano' => $ano,
        'mes' => $mes,
        'distribuidoras' => $filme->distribuidoras ?? [],
        'origem' => $filme->paises ?? [],
        'genero' => $filme->generos ?? [],
        'tecnologia' => $filme->tecnologias ?? []
      ], $distribuidoresBase, [
        $distribuidora => [$filmeData]
      ]);
    }
  }
}
?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<div class="container page-distribuidora">
  <h1>Lançamentos por Distribuidora</h1>
  <section class="grid-select">
    <form method="GET" action="<?php echo home_url(); ?>/lancamentos-por-distribuidora/">
      <div class="grid grid-7-xl gap-22 select-itens">
        <select id="ano" name="ano">
          <option isabled selected value="">Ano</option>
          <?php foreach ($anos as $key => $value) { ?>
          <option value="<?php echo esc_attr($value); ?>"><?php echo esc_html($value); ?></option>
          <?php } ?>
        </select>
        <select name="mes" id="mes">
          <option disabled selected value="">Mês</option>
          <?php foreach ($meses as $key => $value) { ?>
          <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
          <?php } ?>
        </select>
        <select name="origem" id="origem">
          <option disabled selected value="">Origem</option>
          <?php foreach ($paises as $paise) { ?>
          <option value="<?php echo esc_attr($paise->term_id); ?>"><?php echo esc_html($paise->name); ?></option>
          <?php } ?>
        </select>
        <select name="distribuicao" id="distribuidoras">
          <option disabled selected value="">Distribuidor</option>
          <?php foreach ($distribuidoras as $distribuidora) { ?>
          <option value="<?php echo esc_attr($distribuidora->term_id); ?>">
            <?php echo esc_html($distribuidora->name); ?></option>
          <?php } ?>
        </select>
        <select name="genero" id="genero">
          <option disabled selected value="">Gênero</option>
          <?php foreach ($termos as $termo) { ?>
          <option value="<?php echo esc_attr($termo->term_id); ?>"><?php echo esc_html($termo->name); ?></option>
          <?php } ?>
        </select>
        <select name="tecnologia" id="tecnologia">
          <option disabled selected value="">Tecnologia</option>
          <?php foreach ($tecnologias as $tecnologia) { ?>
          <option value="<?php echo esc_attr($tecnologia->term_id); ?>"><?php echo esc_html($tecnologia->name); ?>
          </option>
          <?php } ?>
        </select>
        <button type="submit">Filtrar</button>
      </div>
    </form>
  </section>
  <?php if (!empty($resultData)) : ?>
  <table class="tabela-distribuidora">
    <thead>
      <tr>
        <th>Data de Estreia</th>
        <th>Ano</th>
        <th>Mês</th>
        <!-- Todas as colunas de distribuidoras -->
        <th>Disney</th>
        <th>Paramount</th>
        <th>Sony</th>
        <th>Universal</th>
        <th>Warner</th>
        <th>downtownParis</th>
        <th>Imagem</th>
        <th>Paris</th>
        <th>Diamond</th>
        <th>Outras</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($resultData as $item) : ?>
      <tr>
        <td class="data"><?php echo formatar_data_estreia_dist($item['estreia'] ?? ''); ?> </td>
        <td><?php echo esc_html($item['ano'] ?? ''); ?></td>
        <td><?php echo esc_html(traduzir_mes_para_portugues($item['mes'] ?? '')); ?></td>
        <!-- Todas as células de distribuidoras -->
        <td><?php echo format_filmes($item['Disney'] ?? []); ?></td>
        <td><?php echo format_filmes($item['Paramount'] ?? []); ?></td>
        <td><?php echo format_filmes($item['Sony'] ?? []); ?></td>
        <td><?php echo format_filmes($item['Universal'] ?? []); ?></td>
        <td><?php echo format_filmes($item['Warner'] ?? []); ?></td>
        <td><?php echo format_filmes($item['downtownParis'] ?? []); ?></td>
        <td><?php echo format_filmes($item['Imagem'] ?? []); ?></td>
        <td><?php echo format_filmes($item['Paris'] ?? []); ?></td>
        <td><?php echo format_filmes($item['Diamond'] ?? []); ?></td>
        <td><?php echo format_filmes($item['OutrasDistribuidoras'] ?? []); ?></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <div class="pagination">
    <?php
      echo paginate_links(array(
        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
        'format' => '?paged=%#%',
        'current' => max(1, $paged),
        'total' => $filmes_query->max_num_pages,
        'prev_text' => __('« Anterior'),
        'next_text' => __('Próxima »'),
      ));
      ?>
  </div>
  <?php else : ?>
  <p>Nenhum filme encontrado.</p>
  <?php endif; ?>

</div>
<?php
function formatar_data_estreia_dist($data)
{
  if (empty($data)) {
    return '';
  }

  // Mapeamento dos meses completos em português
  $meses = array(
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
    12 => 'Dezembro'
  );

  // Mapeamento dos dias da semana em português
  $dias_semana = array(
    'Domingo',
    'Segunda-feira',
    'Terça-feira',
    'Quarta-feira',
    'Quinta-feira',
    'Sexta-feira',
    'Sábado'
  );

  $timestamp = strtotime($data);

  if ($timestamp === false) {
    return '';
  }

  $dia = date('d', $timestamp);
  $mes_num = date('n', $timestamp);
  $ano = date('Y', $timestamp);
  $dia_semana_num = date('w', $timestamp);

  $mes_nome = $meses[$mes_num] ?? '';
  $dia_semana = $dias_semana[$dia_semana_num] ?? '';

  return sprintf(
    '
  <div class="dia">
    %s
  </div>
   <div class="mes">
    %s
  </div>
  <div class="ano">
    %s
  </div> %s',
    $dia,
    $mes_nome,
    $ano,
    $dia_semana
  );
}

function format_filmes($filmes)
{
  if (empty($filmes)) return '';

  $output = '<ul>';
  foreach ($filmes as $filme) {
    $output .= '<li>';
    $output .= '<a href="' . esc_url($filme['link'] ?? '#') . '" target="_blank">';
    $output .= esc_html($filme['title'] ?? '');

    if (!empty($filme['titulo_original']) && $filme['titulo_original'] !== $filme['title']) {
      $output .= ' <small>(' . esc_html($filme['titulo_original']) . ')</small>';
    }

    $output .= '</a>';
    $output .= '</li>';
  }
  $output .= '</ul>';

  return $output;
}

function traduzir_mes_para_portugues($mes_ingles)
{
  $meses = [
    'January' => 'Janeiro',
    'February' => 'Fevereiro',
    'March' => 'Março',
    'April' => 'Abril',
    'May' => 'Maio',
    'June' => 'Junho',
    'July' => 'Julho',
    'August' => 'Agosto',
    'September' => 'Setembro',
    'October' => 'Outubro',
    'November' => 'Novembro',
    'December' => 'Dezembro'
  ];

  return $meses[$mes_ingles] ?? $mes_ingles;
}

wp_reset_postdata();
get_footer(); ?>

<?php get_template_part('components/Footer/index'); ?>
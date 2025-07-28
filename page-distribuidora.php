<?php // Template Name: Distribuidora
get_header();

// Obter informações da data atual
$mes_atual_num = date('m');
$mes_atual_nome = date('F');
$semana_atual = date('W');
$ano_atual = date('Y');

// Configuração da consulta com paginação do WordPress
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$posts_per_page = 32;

// Valores selecionados nos filtros
$mes_selecionado = isset($_GET['mes']) ? sanitize_text_field($_GET['mes']) : $mes_atual_num;
$ano_selecionado = isset($_GET['ano']) ? sanitize_text_field($_GET['ano']) : $ano_atual;

// Calcular primeiro e último dia do período selecionado
$primeiro_dia = date("$ano_selecionado-$mes_selecionado-01");
$ultimo_dia = date("$ano_selecionado-$mes_selecionado-t");

// Argumentos base da query
$args = array(
  'post_type' => 'filmes',
  'posts_per_page' => -1, // Traz todos os posts de uma vez
  'meta_key' => 'estreia',
  'orderby' => 'meta_value',
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'key' => 'estreia',
      'value' => array($primeiro_dia, $ultimo_dia),
      'compare' => 'BETWEEN',
      'type' => 'DATE'
    )
  ),
  'tax_query' => array(
    'relation' => 'AND'
  )
);

// Adicionar filtros de taxonomia
if (isset($_GET['origem']) && !empty($_GET['origem'])) {
  $args['meta_query'][] = array(
    'key' => 'paises',
    'value' => sanitize_text_field($_GET['origem']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['distribuicao']) && !empty($_GET['distribuicao'])) {
  $args['meta_query'][] = array(
    'key' => 'distribuicao',
    'value' => sanitize_text_field($_GET['distribuicao']),
    'compare' => '=',
  );
}

if (isset($_GET['genero']) && !empty($_GET['genero'])) {
  $args['meta_query'][] = array(
    'key' => 'generos',
    'value' => sanitize_text_field($_GET['genero']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['tecnologia']) && !empty($_GET['tecnologia'])) {
  $args['meta_query'][] = array(
    'key' => 'tecnologia',
    'value' => sanitize_text_field($_GET['tecnologia']),
    'compare' => 'REGEXP',
  );
}


// Criar a query
$filmes_query = new WP_Query($args);

// Processar os dados
$resultData = array();

// Obter termos para os filtros
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

function obter_anos_dos_filmes() {
  global $wpdb;
  
  // Query otimizada para obter anos distintos
  $results = $wpdb->get_col(
    "SELECT DISTINCT YEAR(meta_value) 
     FROM {$wpdb->postmeta} 
     WHERE meta_key = 'estreia' 
     ORDER BY meta_value DESC"
  );
  
  return $results ?: array(date('Y'));
}

$anos = obter_anos_dos_filmes();

if ($filmes_query->have_posts()) {
  while ($filmes_query->have_posts()) {
    $filmes_query->the_post();

    $filme = filme_scheme(get_post());
    $estreia = $filme->estreia ?? null;
    $sem_data = CFS()->get('sem_data');
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
      'Diamond Films' => 'Diamond',
      'Disney' => 'Disney',
      'Paramount' => 'Paramount',
      'Sony' => 'Sony',
      'Imagem Filmes' => 'Imagem',
      'Universal' => 'Universal',
      'Warner' => 'Warner', 
      'Warner/RioFilme' => 'Warner',
      'Fox / Warner' => 'Warner',
      'downtownParis' => 'downtownParis', 
      'Paris' => 'Paris',
      'Downtown/Paris' => 'downtownParis'
    ];
    $distribuidora = $distribuidorasMap[$distribuidora] ?? 'OutrasDistribuidoras';

    // Dados do filme
    $filmeData = [
      'ID' => get_the_ID(), // Adicione esta linha
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
      $newItem = array_merge([
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
      
      // Verificar se o item atende a todos os filtros
      $include_item = true;
      
      // Verificar filtro de distribuidora
      if (isset($_GET['distribuicao']) && !empty($_GET['distribuicao'])) {
        $term = get_term($_GET['distribuicao'], 'distribuidoras');
        if ($term && !in_array($term->name, $newItem['distribuidoras'])) {
          $include_item = false;
        }
      }
      
      // Verificar filtro de origem
      if (isset($_GET['origem']) && !empty($_GET['origem']) && $include_item) {
        $term = get_term($_GET['origem'], 'paises');
        if ($term && !in_array($term->name, $newItem['origem'])) {
          $include_item = false;
        }
      }
      
      // Verificar filtro de gênero
      if (isset($_GET['genero']) && !empty($_GET['genero']) && $include_item) {
        $term = get_term($_GET['genero'], 'generos');
        if ($term && !in_array($term->name, $newItem['genero'])) {
          $include_item = false;
        }
      }
      
      // Verificar filtro de tecnologia
      if (isset($_GET['tecnologia']) && !empty($_GET['tecnologia']) && $include_item) {
        $term = get_term($_GET['tecnologia'], 'tecnologias');
        if ($term && !in_array($term->name, $newItem['tecnologia'])) {
          $include_item = false;
        }
      }
      
      if ($include_item) {
        $resultData[] = $newItem;
      }
    }
  }
}
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

<div class="container page-distribuidora">
  <h1>Lançamentos por Distribuidora</h1>
  <section class="grid-select">
    <form method="GET" action="<?php echo home_url(); ?>/lancamentos-por-distribuidora/">
      <div class="grid grid-7-xl gap-22 select-itens">
        <select id="ano" name="ano">
          <option disabled value="">Ano</option>
          <?php foreach ($anos as $key => $value) { ?>
          <option value="<?php echo esc_attr($value); ?>" <?php echo ($value == $ano_selecionado) ? 'selected' : ''; ?>>
            <?php echo esc_html($value); ?>
          </option>
          <?php } ?>
        </select>

        <select name="mes" id="mes">
          <option disabled value="">Mês</option>
          <?php foreach ($meses as $key => $value) { ?>
          <option value="<?php echo esc_attr($key); ?>" <?php echo ($key == $mes_selecionado) ? 'selected' : ''; ?>>
            <?php echo esc_html($value); ?>
          </option>
          <?php } ?>
        </select>

        <select name="origem" id="origem">
          <option disabled selected value="">Origem</option>
          <?php foreach ($paises as $paise) { ?>
          <option value="<?php echo esc_attr($paise->term_id); ?>"
            <?php echo (isset($_GET['origem']) && $_GET['origem'] == $paise->term_id) ? 'selected' : ''; ?>>
            <?php echo esc_html($paise->name); ?>
          </option>
          <?php } ?>
        </select>

        <select name="distribuicao" id="distribuidoras">
          <option disabled selected value="">Distribuidor</option>
          <?php foreach ($distribuidoras as $distribuidora) { ?>
          <option value="<?php echo esc_attr($distribuidora->term_id); ?>"
            <?php echo (isset($_GET['distribuicao']) && $_GET['distribuicao'] == $distribuidora->term_id) ? 'selected' : ''; ?>>
            <?php echo esc_html($distribuidora->name); ?>
          </option>
          <?php } ?>
        </select>

        <select name="genero" id="genero">
          <option disabled selected value="">Gênero</option>
          <?php foreach ($termos as $termo) { ?>
          <option value="<?php echo esc_attr($termo->term_id); ?>"
            <?php echo (isset($_GET['genero']) && $_GET['genero'] == $termo->term_id) ? 'selected' : ''; ?>>
            <?php echo esc_html($termo->name); ?>
          </option>
          <?php } ?>
        </select>

        <select name="tecnologia" id="tecnologia">
          <option disabled selected value="">Tecnologia</option>
          <?php foreach ($tecnologias as $tecnologia) { ?>
          <option value="<?php echo esc_attr($tecnologia->term_id); ?>"
            <?php echo (isset($_GET['tecnologia']) && $_GET['tecnologia'] == $tecnologia->term_id) ? 'selected' : ''; ?>>
            <?php echo esc_html($tecnologia->name); ?>
          </option>
          <?php } ?>
        </select>

        <button type="submit">Filtrar</button>
        <a href="<?php echo get_site_url(); ?>/filmes/" type="submit">Voltar</a>
      </div>
    </form>
  </section>
</div>
<?php if (!empty($resultData)) : ?>
<section class="area-tabela">
  <table class="tabela-distribuidora">
    <thead>
      <tr>
        <th>Data de Estreia</th>
        <th>Disney</th>
        <th>Paramount</th>
        <th>Sony</th>
        <th>Universal</th>
        <th>Warner</th>
        <th>Downtown</th>
        <th>Imagem</th>
        <th>Paris</th>
        <th>Diamond</th>
        <th>Outras</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($resultData as $item) : ?>
      <tr data-date="<?php echo esc_attr($item['estreia'] ?? ''); ?>">
        <td class="data"><?php echo formatar_data_estreia_dist($item['estreia'] ?? ''); ?></td>

        <td><?php echo !empty($item['Disney']) ? format_filmes($item['Disney']) : ''; ?></td>
        <td><?php echo !empty($item['Paramount']) ? format_filmes($item['Paramount']) : ''; ?></td>
        <td><?php echo !empty($item['Sony']) ? format_filmes($item['Sony']) : ''; ?></td>
        <td><?php echo !empty($item['Universal']) ? format_filmes($item['Universal']) : ''; ?></td>
        <td><?php echo !empty($item['Warner']) ? format_filmes($item['Warner']) : ''; ?></td>
        <td><?php echo !empty($item['downtownParis']) ? format_filmes($item['downtownParis']) : ''; ?></td>
        <td><?php echo !empty($item['Imagem']) ? format_filmes($item['Imagem']) : ''; ?></td>
        <td><?php echo !empty($item['Paris']) ? format_filmes($item['Paris']) : ''; ?></td>
        <td><?php echo !empty($item['Diamond']) ? format_filmes($item['Diamond']) : ''; ?></td>
        <td><?php echo !empty($item['OutrasDistribuidoras']) ? format_filmes($item['OutrasDistribuidoras']) : ''; ?>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</section>
<div class="container">
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
</div>

<?php else : ?>
<p>Nenhum filme encontrado para o período selecionado.</p>
<?php endif; ?>


<script>
document.addEventListener('DOMContentLoaded', function() {
  // Função para calcular a semana do ano
  function getWeekNumber(date) {
    const d = new Date(date);
    d.setHours(0, 0, 0, 0);
    d.setDate(d.getDate() + 4 - (d.getDay() || 7));
    const yearStart = new Date(d.getFullYear(), 0, 1);
    const weekNo = Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    return weekNo;
  }

  // Data atual
  const today = new Date();
  const currentWeek = getWeekNumber(today);

  // Encontrar todas as linhas da tabela
  const rows = document.querySelectorAll('.tabela-distribuidora tbody tr');
  let currentWeekRow = null;
  let closestRow = null;
  let smallestDiff = Infinity;

  // Procurar a linha da semana atual ou a mais próxima
  rows.forEach(row => {
    const dateStr = row.getAttribute('data-date');
    if (dateStr) {
      const rowDate = new Date(dateStr);
      const rowWeek = getWeekNumber(rowDate);

      // Calcular diferença em semanas
      const weekDiff = Math.abs(rowWeek - currentWeek);

      // Se for a semana exata
      if (rowWeek === currentWeek) {
        currentWeekRow = row;
      }
      // Se não encontrou exata, guarda a mais próxima
      else if (weekDiff < smallestDiff) {
        smallestDiff = weekDiff;
        closestRow = row;
      }
    }
  });

  // Prioridade para a semana exata, senão usa a mais próxima
  const targetRow = currentWeekRow || closestRow;

  // Se encontrou uma linha para rolar
  if (targetRow) {
    // Aguardar um pouco para garantir que a tabela está renderizada
    setTimeout(() => {
      targetRow.scrollIntoView({
        behavior: 'smooth',
        block: 'center'
      });

      // Adicionar classe de destaque (opcional)
      targetRow.classList.add('current-week');
    }, 300);
  }
});
</script>

<?php
function formatar_data_estreia_dist($data) {
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

function format_filmes($filmes) {
  if (empty($filmes)) return '';

  $output = '<ul>';
  foreach ($filmes as $filme) {
    // Obter o valor de sem_data para o filme atual
    $sem_data = get_post_meta($filme['ID'], 'sem_data', true);
    $classe = ($sem_data == 1) ? 'alterado' : '';

    $output .= '<li>';
    $output .= '<a href="' . esc_url($filme['link'] ?? '#') . '" target="_blank" class="' . esc_attr($classe) . '">';
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
function traduzir_mes_para_portugues($mes_ingles) {
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
<?php // Template Name: Distribuidora
get_header();

// Obter mês e ano atual
$current_date = new DateTime();
$current_year = $current_date->format('Y');
$current_month = $current_date->format('m');

// Configuração da consulta com paginação do WordPress
$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$posts_per_page = 32;

// Argumentos base para pegar filmes do mês/ano atual
$args = array(
    'post_type' => 'filmes',
    'posts_per_page' => $posts_per_page,
    'paged' => $paged,
    'meta_key' => 'estreia',
    'orderby' => 'meta_value',
    'order' => 'DESC',
    'meta_type' => 'DATE',
    'meta_query' => array(
        array(
            'key' => 'estreia',
            'value' => array($current_year . '-' . $current_month . '-01', $current_year . '-' . $current_month . '-31'),
            'compare' => 'BETWEEN',
            'type' => 'DATE'
        )
    )
);

// Filtros
if (isset($_GET['ano']) && !empty($_GET['ano'])) {
    $selected_year = sanitize_text_field($_GET['ano']);
    $args['meta_query'][0]['value'] = array($selected_year . '-' . $current_month . '-01', $selected_year . '-' . $current_month . '-31');
}

if (isset($_GET['mes']) && !empty($_GET['mes'])) {
    $selected_month = sanitize_text_field($_GET['mes']);
    $year_to_use = isset($_GET['ano']) ? sanitize_text_field($_GET['ano']) : $current_year;
    $args['meta_query'][0]['value'] = array($year_to_use . '-' . $selected_month . '-01', $year_to_use . '-' . $selected_month . '-31');
}

if (isset($_GET['origem']) && !empty($_GET['origem'])) {
    $args['tax_query'][] = array(
        'taxonomy' => 'paises',
        'field' => 'term_id',
        'terms' => sanitize_text_field($_GET['origem']),
    );
}

if (isset($_GET['distribuicao']) && !empty($_GET['distribuicao'])) {
    $args['tax_query'][] = array(
        'taxonomy' => 'distribuidoras',
        'field' => 'term_id',
        'terms' => sanitize_text_field($_GET['distribuicao']),
    );
}

if (isset($_GET['genero']) && !empty($_GET['genero'])) {
    $args['tax_query'][] = array(
        'taxonomy' => 'generos',
        'field' => 'term_id',
        'terms' => sanitize_text_field($_GET['genero']),
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

$filmes_query = new WP_Query($args);

// Processar os dados
$resultData = array();

// Obter termos para filtros
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
    '01' => 'Janeiro', '02' => 'Fevereiro', '03' => 'Março',
    '04' => 'Abril', '05' => 'Maio', '06' => 'Junho',
    '07' => 'Julho', '08' => 'Agosto', '09' => 'Setembro',
    '10' => 'Outubro', '11' => 'Novembro', '12' => 'Dezembro'
];

function obter_anos_dos_filmes() {
    global $wpdb;
    
    $query = "SELECT DISTINCT YEAR(meta_value) as year 
              FROM {$wpdb->postmeta} 
              WHERE meta_key = 'estreia' 
              ORDER BY year DESC";
    
    $results = $wpdb->get_col($query);
    
    return $results ?: [];
}

$anos = obter_anos_dos_filmes();

if ($filmes_query->have_posts()) {
    while ($filmes_query->have_posts()) {
        $filmes_query->the_post();

        $filme = filme_scheme(get_post());
        $estreia = $filme->estreia ?? null;

        if (!$estreia) continue;

        try {
            $dataEstreia = new DateTime($estreia);
            $ano_mes = $dataEstreia->format('Y-m');
            $dia = (int)$dataEstreia->format('d');
            $mes_nome = $dataEstreia->format('F');
            $ano = $dataEstreia->format('Y');
        } catch (Exception $e) {
            continue;
        }

        // Configuração das distribuidoras
        $distribuidoresBase = [
            'Disney' => [], 'Paramount' => [], 'Sony' => [],
            'Universal' => [], 'Warner' => [], 'downtownParis' => [],
            'Imagem' => [], 'Paris' => [], 'Diamond' => [],
            'OutrasDistribuidoras' => []
        ];

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

        $filmeData = [
            'link' => $filme->link ?? '',
            'title' => $filme->title ?? '',
            'titulo_original' => $filme->titulo_original ?? '',
            'dia' => $dia
        ];

        // Organizar por ano-mês-dia
        if (!isset($resultData[$ano_mes])) {
            $resultData[$ano_mes] = [
                'ano' => $ano,
                'mes' => $mes_nome,
                'dias' => []
            ];
        }

        if (!isset($resultData[$ano_mes]['dias'][$dia])) {
            $resultData[$ano_mes]['dias'][$dia] = [
                'estreia' => $estreia,
                'distribuidoras' => $distribuidoresBase
            ];
        }

        $resultData[$ano_mes]['dias'][$dia]['distribuidoras'][$distribuidora][] = $filmeData;
    }
}

// Ordenar dias em ordem decrescente dentro de cada mês (do maior para o menor)
foreach ($resultData as &$mesData) {
    krsort($mesData['dias']);
}

// Ordenar meses em ordem decrescente
krsort($resultData);
?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<div class="container page-distribuidora">
  <h1>Lançamentos por Distribuidora</h1>

  <section class="grid-select">
    <form method="GET" action="<?php echo home_url(); ?>/lancamentos-por-distribuidora/">
      <div class="grid grid-7-xl gap-22 select-itens">
        <select id="ano" name="ano">
          <option disabled selected value="">Ano</option>
          <?php foreach ($anos as $ano) : ?>
          <option value="<?php echo esc_attr($ano); ?>" <?php selected(isset($_GET['ano']) && $_GET['ano'] == $ano); ?>>
            <?php echo esc_html($ano); ?>
          </option>
          <?php endforeach; ?>
        </select>

        <select name="mes" id="mes">
          <option disabled selected value="">Mês</option>
          <?php foreach ($meses as $key => $value) : ?>
          <option value="<?php echo esc_attr($key); ?>" <?php selected(isset($_GET['mes']) && $_GET['mes'] == $key); ?>>
            <?php echo esc_html($value); ?>
          </option>
          <?php endforeach; ?>
        </select>

        <select name="origem" id="origem">
          <option disabled selected value="">Origem</option>
          <?php foreach ($paises as $pais) : ?>
          <option value="<?php echo esc_attr($pais->term_id); ?>"
            <?php selected(isset($_GET['origem']) && $_GET['origem'] == $pais->term_id); ?>>
            <?php echo esc_html($pais->name); ?>
          </option>
          <?php endforeach; ?>
        </select>

        <select name="distribuicao" id="distribuidoras">
          <option disabled selected value="">Distribuidor</option>
          <?php foreach ($distribuidoras as $distribuidora) : ?>
          <option value="<?php echo esc_attr($distribuidora->term_id); ?>"
            <?php selected(isset($_GET['distribuicao']) && $_GET['distribuicao'] == $distribuidora->term_id); ?>>
            <?php echo esc_html($distribuidora->name); ?>
          </option>
          <?php endforeach; ?>
        </select>

        <select name="genero" id="genero">
          <option disabled selected value="">Gênero</option>
          <?php foreach ($termos as $termo) : ?>
          <option value="<?php echo esc_attr($termo->term_id); ?>"
            <?php selected(isset($_GET['genero']) && $_GET['genero'] == $termo->term_id); ?>>
            <?php echo esc_html($termo->name); ?>
          </option>
          <?php endforeach; ?>
        </select>

        <select name="tecnologia" id="tecnologia">
          <option disabled selected value="">Tecnologia</option>
          <?php foreach ($tecnologias as $tecnologia) : ?>
          <option value="<?php echo esc_attr($tecnologia->term_id); ?>"
            <?php selected(isset($_GET['tecnologia']) && $_GET['tecnologia'] == $tecnologia->term_id); ?>>
            <?php echo esc_html($tecnologia->name); ?>
          </option>
          <?php endforeach; ?>
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
      <?php foreach ($resultData as $ano_mes => $mesData) : ?>
      <?php foreach ($mesData['dias'] as $dia => $diaData) : ?>
      <tr>
        <td class="data"><?php echo formatar_data_estreia_dist($diaData['estreia']); ?></td>
        <td><?php echo esc_html($mesData['ano']); ?></td>
        <td><?php echo esc_html(traduzir_mes_para_portugues($mesData['mes'])); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['Disney'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['Paramount'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['Sony'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['Universal'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['Warner'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['downtownParis'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['Imagem'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['Paris'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['Diamond'] ?? []); ?></td>
        <td><?php echo format_filmes($diaData['distribuidoras']['OutrasDistribuidoras'] ?? []); ?></td>
      </tr>
      <?php endforeach; ?>
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
function formatar_data_estreia_dist($data) {
    if (empty($data)) return '';

    $meses = array(
        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março',
        4 => 'Abril', 5 => 'Maio', 6 => 'Junho',
        7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro',
        10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
    );

    $dias_semana = array(
        'Domingo', 'Segunda-feira', 'Terça-feira',
        'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'
    );

    $timestamp = strtotime($data);
    if ($timestamp === false) return '';

    $dia = date('d', $timestamp);
    $mes_num = date('n', $timestamp);
    $ano = date('Y', $timestamp);
    $dia_semana_num = date('w', $timestamp);

    $mes_nome = $meses[$mes_num] ?? '';
    $dia_semana = $dias_semana[$dia_semana_num] ?? '';

    return sprintf(
        '<div class="dia">%s</div>
         <div class="mes">%s</div>
         <div class="ano">%s</div>
         <div class="dia-semana">%s</div>',
        $dia, $mes_nome, $ano, $dia_semana
    );
}

function format_filmes($filmes) {
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

function traduzir_mes_para_portugues($mes_ingles) {
    $meses = [
        'January' => 'Janeiro', 'February' => 'Fevereiro',
        'March' => 'Março', 'April' => 'Abril',
        'May' => 'Maio', 'June' => 'Junho',
        'July' => 'Julho', 'August' => 'Agosto',
        'September' => 'Setembro', 'October' => 'Outubro',
        'November' => 'Novembro', 'December' => 'Dezembro'
    ];

    return $meses[$mes_ingles] ?? $mes_ingles;
}

wp_reset_postdata();
get_footer();
?>

<?php get_template_part('components/Footer/index'); ?>
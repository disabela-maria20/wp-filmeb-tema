<?php get_header(); ?>

<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);

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

$dias_semana = [
  'Sunday'    => 'Domingo',
  'Monday'    => 'Segunda-feira',
  'Tuesday'   => 'Terça-feira',
  'Wednesday' => 'Quarta-feira',
  'Thursday'  => 'Quinta-feira',
  'Friday'    => 'Sexta-feira',
  'Saturday'  => 'Sábado',
];

// Valores padrão para ano e mês
$current_year = date('Y');
$current_month = date('m');
$selected_ano = isset($_GET['ano']) ? sanitize_text_field($_GET['ano']) : $current_year;
$selected_mes = isset($_GET['mes']) ? sanitize_text_field($_GET['mes']) : $current_month;

// Verificar se há filtros ativos
$has_filters = isset($_GET['ano']) || isset($_GET['mes']) || isset($_GET['origem']) || 
               isset($_GET['distribuicao']) || isset($_GET['genero']) || isset($_GET['tecnologia']);

// Verificar se foi selecionado "Todos" no mês
$mostrar_todos = isset($_GET['mes']) && $_GET['mes'] === 'todos';

// Argumentos para buscar filmes
$args_mes = array(
  'post_type' => 'filmes',
  'posts_per_page' => -1,
  'post_status' => 'publish',
  'orderby' => 'meta_value',
  'meta_key' => 'estreia',
  'order' => 'ASC'
);

// Apenas adicionar filtros de data se não for selecionado "todos" no mês
if ($selected_mes !== 'todos') {
  $args_mes['meta_query'] = array(
    'relation' => 'AND',
    array(
      'key' => 'estreia',
      'value' => '^' . $selected_ano,
      'compare' => 'REGEXP',
    ),
    array(
      'key' => 'estreia',
      'value' => '-' . $selected_mes . '-',
      'compare' => 'REGEXP',
    )
  );
} else {
  // Se mês for "todos", filtrar apenas pelo ano
  $args_mes['meta_query'] = array(
    array(
      'key' => 'estreia',
      'value' => '^' . $selected_ano,
      'compare' => 'REGEXP',
    )
  );
}

function apply_filters_to_args($args) {
  if (!isset($args['meta_query'])) {
    $args['meta_query'] = array();
  }

  if (isset($_GET['origem']) && !empty($_GET['origem']) && $_GET['origem'] !== 'todos') {
    $args['meta_query'][] = array(
      'key' => 'paises',
      'value' => sanitize_text_field($_GET['origem']),
      'compare' => 'REGEXP',
    );
  }

  if (isset($_GET['distribuicao']) && !empty($_GET['distribuicao']) && $_GET['distribuicao'] !== 'todos') {
    $args['meta_query'][] = array(
      'key' => 'distribuicao',
      'value' => sanitize_text_field($_GET['distribuicao']),
      'compare' => '=',
    );
  }

  if (isset($_GET['genero']) && !empty($_GET['genero']) && $_GET['genero'] !== 'todos') {
    $args['meta_query'][] = array(
      'key' => 'generos',
      'value' => sanitize_text_field($_GET['genero']),
      'compare' => 'REGEXP',
    );
  }

  if (isset($_GET['tecnologia']) && !empty($_GET['tecnologia']) && $_GET['tecnologia'] !== 'todos') {
    $args['meta_query'][] = array(
      'key' => 'tecnologia',
      'value' => sanitize_text_field($_GET['tecnologia']),
      'compare' => 'REGEXP',
    );
  }
  
  return $args;
}

$args_mes = apply_filters_to_args($args_mes);

// Buscar filmes
$filmes_mes = new WP_Query($args_mes);

function agrupar_filmes_por_categoria($wp_query, $mostrar_todos = false) {
    $agrupados = array(
        'semana_atual' => array(),
        'proxima_semana' => array(),
        'semanas_seguintes' => array(),
        'sem_data' => array(),
        'semanas_passadas' => array(),
        'todos_ordenados' => array()
    );
    
    $hoje = new DateTime();
    $semana_atual_inicio = clone $hoje;
    $semana_atual_inicio->modify('monday this week');
    $semana_atual_fim = clone $semana_atual_inicio;
    $semana_atual_fim->modify('+6 days');
    
    $proxima_semana_inicio = clone $semana_atual_inicio;
    $proxima_semana_inicio->modify('+7 days');
    $proxima_semana_fim = clone $proxima_semana_inicio;
    $proxima_semana_fim->modify('+6 days');

    if ($wp_query->have_posts()) {
        while ($wp_query->have_posts()) {
            $wp_query->the_post();
            $post_id = get_the_ID();
            $data_estreia = CFS()->get('estreia', $post_id);
            $tags = wp_get_post_tags($post_id);
            $tem_tag_sem_data = false;

            // Verificar se tem a tag "sem data"
            foreach ($tags as $tag) {
                if (strtolower($tag->name) === 'sem data') {
                    $tem_tag_sem_data = true;
                    break;
                }
            }

            if ($tem_tag_sem_data) {
                $agrupados['sem_data'][] = $post_id;
                $agrupados['todos_ordenados'][$post_id] = '9999-99-99';
            } elseif ($data_estreia && preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_estreia)) {
                $data_obj = DateTime::createFromFormat('Y-m-d', $data_estreia);
                
                // Adicionar sempre ao array todos_ordenados
                $agrupados['todos_ordenados'][$post_id] = $data_estreia;
                
                if (!$mostrar_todos) {
                    if ($data_obj >= $semana_atual_inicio && $data_obj <= $semana_atual_fim) {
                        $agrupados['semana_atual'][$data_estreia][] = $post_id;
                    }
                    elseif ($data_obj >= $proxima_semana_inicio && $data_obj <= $proxima_semana_fim) {
                        $agrupados['proxima_semana'][$data_estreia][] = $post_id;
                    } 
                    elseif ($data_obj > $proxima_semana_fim) {
                        $agrupados['semanas_seguintes'][$data_estreia][] = $post_id;
                    }
                    elseif ($data_obj < $semana_atual_inicio) {
                        $agrupados['semanas_passadas'][$data_estreia][] = $post_id;
                    }
                }
            }
        }
        wp_reset_postdata();
    }
    
    // Ordenar semanas seguintes em ordem crescente
    if (!empty($agrupados['semanas_seguintes'])) {
        ksort($agrupados['semanas_seguintes']);
    }
    
    // Ordenar semanas passadas em ordem decrescente
    if (!empty($agrupados['semanas_passadas'])) {
        krsort($agrupados['semanas_passadas']);
    }
    
    // Ordenar todos os filmes por data (do mais recente para o mais antigo)
    if (!empty($agrupados['todos_ordenados'])) {
        asort($agrupados['todos_ordenados']);
    }
    
    return $agrupados;
}

function render_terms($field_key, $post_id) {
  $terms = CFS()->get($field_key, $post_id);
  $output = '';
  if (!empty($terms)) {
    foreach ($terms as $term_id) {
      $term = get_term($term_id);
      if ($term && !is_wp_error($term)) {
        $output .= '<div>' . esc_html($term->name) . '</div>';
      }
    }
  }
  return $output;
}

function obter_anos_dos_filmes() {
  global $wpdb;
  
  $results = $wpdb->get_col(
    "SELECT DISTINCT YEAR(meta_value) 
     FROM {$wpdb->postmeta} 
     WHERE meta_key = 'estreia' 
     AND meta_value != '' 
     ORDER BY YEAR(meta_value) DESC"
  );
  
  return $results;
}

$anos = obter_anos_dos_filmes();
$filmes_agrupados = agrupar_filmes_por_categoria($filmes_mes, $mostrar_todos);
?>

<?php
$banner_id = "78847";

$banner_superior = CFS()->get('banner_moldura', $banner_id);
$banner_inferior = CFS()->get('mega_banner', $banner_id);
$skyscraper = CFS()->get('skyscraper', $banner_id);
$big_stamp = CFS()->get('big_stamp', $banner_id);
$banner_moldura_casado = CFS()->get('banner_moldura_casado', $banner_id);

$link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
$link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
$link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
$link_big_stampr = CFS()->get('link_big_stamp', $banner_id);
$link_banner_moldura_casado = CFS()->get('link_banner_moldura_casado', $banner_id);
?>
<a href="<?php echo esc_url($link_banner_superior) ?>" target="_blank" rel="noopener noreferrer">
  <img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile " alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </a>
  </div>
</div>


<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <a href="<?php echo $link_banner_inferior; ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
    </div>
  </div>
</section>

<div class="container page-filmes">
  <div id="app">
    <div class="page-filmes">
      <h1>Lançamentos</h1>
      <div class="grid-filtros-config">
        <div class="ordem">
          <button aria-label="ordem 1" @click="setTabAtivo('lista')"><i class="bi bi-border-all"></i></button>
          <button aria-label="ordem 2" @click="setTabAtivo('tabela')"><i class="bi bi-grid-1x2"></i></button>
          <button aria-label="imprimir" onclick="window.print()"><i class="bi bi-printer"></i></button>
        </div>
        <div></div>
        <div class="lancamento">
          <a href="<?php echo home_url(); ?>/lancamentos-por-distribuidora/" id="distribuidora">Ver lançamentos por
            distribuidora</a>
        </div>
      </div>
      <section class="grid-select">
        <form method="GET" action="<?php echo home_url(); ?>/filmes/">
          <div class="grid grid-7-xl gap-22 select-itens">
            <select id="ano" name="ano" v-model="selectedFilters.ano">
              <option value="" disabled>Ano</option>
              <option value="todos">Todos</option>
              <?php foreach ($anos as $value) : ?>
              <option value="<?php echo esc_attr($value); ?>" <?php selected($value, $selected_ano); ?>>
                <?php echo esc_html($value); ?>
              </option>
              <?php endforeach; ?>
            </select>

            <select name="mes" id="mes" v-model="selectedFilters.mes">
              <option value="" disabled>Mês</option>
              <option value="todos">Todos</option>
              <?php foreach ($meses as $key => $value) : ?>
              <option value="<?php echo esc_attr($key); ?>" <?php selected($key, $selected_mes); ?>>
                <?php echo esc_html($value); ?>
              </option>
              <?php endforeach; ?>
            </select>
            <select name="origem" id="origem" v-model="selectedFilters.origem">
              <option value="" disabled>Origem</option>
              <option value="todos">Todos</option>
              <?php foreach ($paises as $paise) { ?>
              <option value="<?php echo esc_attr($paise->term_id); ?>"
                <?php selected($paise->term_id, isset($_GET['origem']) ? $_GET['origem'] : ''); ?>>
                <?php echo esc_html($paise->name); ?></option>
              <?php } ?>
            </select>
            <select name="distribuicao" id="distribuidoras" v-model="selectedFilters.distribuicao">
              <option value="" disabled>Distribuidor</option>
              <option value="todos">Todos</option>
              <?php foreach ($distribuidoras as $distribuidora) { ?>
              <option value="<?php echo esc_attr($distribuidora->term_id); ?>"
                <?php selected($distribuidora->term_id, isset($_GET['distribuicao']) ? $_GET['distribuicao'] : ''); ?>>
                <?php echo esc_html($distribuidora->name); ?></option>
              <?php } ?>
            </select>
            <select name="genero" id="genero" v-model="selectedFilters.genero">
              <option value="" disabled>Gênero</option>
              <option value="todos">Todos</option>
              <?php foreach ($termos as $termo) { ?>
              <option value="<?php echo esc_attr($termo->term_id); ?>"
                <?php selected($termo->term_id, isset($_GET['genero']) ? $_GET['genero'] : ''); ?>>
                <?php echo esc_html($termo->name); ?></option>
              <?php } ?>
            </select>
            <select name="tecnologia" id="tecnologia" v-model="selectedFilters.tecnologia">
              <option value="" disabled>Tecnologia</option>
              <option value="todos">Todos</option>
              <?php foreach ($tecnologias as $tecnologia) { ?>
              <option value="<?php echo esc_attr($tecnologia->term_id); ?>"
                <?php selected($tecnologia->term_id, isset($_GET['tecnologia']) ? $_GET['tecnologia'] : ''); ?>>
                <?php echo esc_html($tecnologia->name); ?>
              </option>
              <?php } ?>
            </select>
            <button type="submit">Filtrar</button>
            <a href="<?php echo get_site_url(); ?>/filmes/" @click.prevent="resetFilters">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                class="bi bi-trash3-fill" viewBox="0 0 16 16">
                <path
                  d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5" />
              </svg>
            </a>
          </div>
        </form>
      </section>

      <?php
      function render_dia_filmes($data, $filmes_ids, $dias_semana) {
          $data_estreia = DateTime::createFromFormat('Y-m-d', $data);
          $dia_semana_ingles = $data_estreia->format('l');
          $dia_semana = $dias_semana[$dia_semana_ingles] ?? $dia_semana_ingles;
          $dia = $data_estreia->format('d');
          $mes = $data_estreia->format('m');
          $ano = $data_estreia->format('Y');
          ?>
      <h2>
        <i class="bi bi-calendar-check-fill"></i><?= esc_html($dia_semana); ?>,
        <?= esc_html($dia); ?>/<?= esc_html($mes); ?>/<?= esc_html($ano); ?>
      </h2>
      <div class="grid-filmes">
        <?php foreach ($filmes_ids as $post_id) {
                  render_card_filme($post_id);
              } ?>
      </div>
      <?php
      }

      function render_card_filme($post_id) {
          $cartaz = esc_url(CFS()->get('cartaz', $post_id));
          ?>
      <a v-on:mousemove="hoverCard" href="<?= get_permalink($post_id); ?>" class="card">
        <?php if ($cartaz === '') : ?>
        <h3><?= get_the_title($post_id); ?></h3>
        <p class="indisponivel">Poster não disponível</p>
        <?php else : ?>
        <img src="<?= $cartaz; ?>" alt="<?= get_the_title($post_id); ?>">
        <?php endif; ?>

        <div class="info">
          <ul>
            <li><span>Título:</span><strong><?= get_the_title($post_id); ?></strong></li>
            <?php if ($d = render_terms('distribuicao', $post_id)) : ?>
            <li><span>Distribuição:</span><strong><?= $d; ?></strong></li>
            <?php endif; ?>
            <?php if ($p = render_terms('paises', $post_id)) : ?>
            <li><span>País:</span><strong><?= $p; ?></strong></li>
            <?php endif; ?>
            <?php if ($g = render_terms('generos', $post_id)) : ?>
            <li><span>Gênero(s):</span><strong><?= $g; ?></strong></li>
            <?php endif; ?>

            <li>
              <?php 
                          $diretores = CFS()->get('direcao', $post_id);
                          if (!empty($diretores)) : ?>
              <span>Direção:</span>
              <strong>
                <?php 
                              $nomes_diretores = array();
                              if (is_array($diretores)) {
                                  foreach ($diretores as $diretor) {
                                      if (is_array($diretor) && isset($diretor['nome'])) {
                                          $nomes_diretores[] = esc_html($diretor['nome']);
                                      } elseif (is_string($diretor)) {
                                          $nomes_diretores[] = esc_html($diretor);
                                      }
                                  }
                                  echo implode(', ', $nomes_diretores);
                              } elseif (is_string($diretores)) {
                                  echo esc_html($diretores);
                              }
                              ?>
              </strong>
              <?php endif; ?>
            </li>
            <?php if ($dur = CFS()->get('duracao_minutos', $post_id)) : ?>
            <li><span>Duração:</span><strong><?= esc_html($dur); ?> min</strong></li>
            <?php endif; ?>
          </ul>
        </div>
      </a>
      <?php
      }

      function render_filmes_lista($filmes_agrupados, $dias_semana, $has_filters = false, $mostrar_todos = false) {
          if ($mostrar_todos) {
            echo '<div class="lista-completa">';
            
            // Reorganiza por datas para agrupar os filmes com mesma data
            $filmes_por_data = [];

            foreach ($filmes_agrupados['todos_ordenados'] as $post_id => $data_estreia) {
                if (!isset($filmes_por_data[$data_estreia])) {
                    $filmes_por_data[$data_estreia] = [];
                }
                $filmes_por_data[$data_estreia][] = $post_id;
            }

            foreach ($filmes_por_data as $data_estreia => $filmes_ids) {
                if ($data_estreia === '9999-99-99') {
                    echo '<h2 class="section-title">Filmes sem data definida</h2>';
                    echo '<div class="grid-filmes">';
                    foreach ($filmes_ids as $post_id) {
                        render_card_filme($post_id);
                    }
                    echo '</div>';
                } else {
                    render_dia_filmes($data_estreia, $filmes_ids, $dias_semana);
                }
            }

            echo '</div>';
        } else {
              // Semana atual
              if (!empty($filmes_agrupados['semana_atual'])) {
                  echo '<h2 class="section-title">Filmes desta semana</h2>';
                  foreach ($filmes_agrupados['semana_atual'] as $data => $filmes_ids) {
                      render_dia_filmes($data, $filmes_ids, $dias_semana);
                  }
              }

              // Próxima semana
              if (!empty($filmes_agrupados['proxima_semana'])) {
                  echo '<h2 class="section-title">Próxima semana</h2>';
                  foreach ($filmes_agrupados['proxima_semana'] as $data => $filmes_ids) {
                      render_dia_filmes($data, $filmes_ids, $dias_semana);
                  }
              }

              // Semanas seguintes
              if (!empty($filmes_agrupados['semanas_seguintes'])) {
                  echo '<h2 class="section-title">Semanas seguintes</h2>';
                  foreach ($filmes_agrupados['semanas_seguintes'] as $data => $filmes_ids) {
                      render_dia_filmes($data, $filmes_ids, $dias_semana);
                  }
              }

              // Filmes sem data
              if (!empty($filmes_agrupados['sem_data'])) {
                  echo '<h2 class="section-title">Filmes sem data definida</h2>';
                  echo '<div class="grid-filmes">';
                  foreach ($filmes_agrupados['sem_data'] as $post_id) {
                      render_card_filme($post_id);
                  }
                  echo '</div>';
              }

              // Semanas que já passaram
              if (!empty($filmes_agrupados['semanas_passadas'])) {
                  echo '<h2 class="section-title">Lançamentos recentes</h2>';
                  $semanas_passadas = array_slice($filmes_agrupados['semanas_passadas'], 0, 3, true);
                  foreach ($semanas_passadas as $data => $filmes_ids) {
                      render_dia_filmes($data, $filmes_ids, $dias_semana);
                  }
              }
          }
          
          // Mensagem quando não há resultados com filtros
          if ($has_filters && empty($filmes_agrupados['semana_atual']) && 
              empty($filmes_agrupados['proxima_semana']) && 
              empty($filmes_agrupados['semanas_seguintes']) && 
              empty($filmes_agrupados['sem_data']) && 
              empty($filmes_agrupados['semanas_passadas']) &&
              empty($filmes_agrupados['todos_ordenados'])) {
              echo '<div class="no-results">';
              echo '<p>Nenhum filme encontrado com os filtros selecionados.</p>';
              echo '</div>';
          }
      }

      function render_dia_tabela($data, $filmes_ids, $dias_semana) {
          $data_estreia = DateTime::createFromFormat('Y-m-d', $data);
          $dia_semana_ingles = $data_estreia->format('l');
          $dia_semana = $dias_semana[$dia_semana_ingles] ?? $dia_semana_ingles;
          $dia = $data_estreia->format('d');
          $mes = $data_estreia->format('m');
          $ano = $data_estreia->format('Y');
          ?>
      <h2><i class="bi bi-calendar-check-fill"></i> <?= esc_html($dia_semana); ?>,
        <?= esc_html($dia); ?>/<?= esc_html($mes); ?>/<?= esc_html($ano); ?></h2>

      <table>
        <thead>
          <tr>
            <th colspan="2" style="width: 20%;">Título</th>
            <th>Distribuição</th>
            <th>Direção</th>
            <th>País</th>
            <th>Gênero</th>
            <th style="width: 5%;">Duração</th>
            <th>Elenco</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($filmes_ids as $post_id) {
                      render_linha_tabela($post_id);
                  } ?>
        </tbody>
      </table>
      <?php
      }

      function render_linha_tabela($post_id) {
          $filme = get_post($post_id);
          ?>
      <tr>
        <td class="titulo" colspan="2">
          <a href="<?= get_permalink($post_id); ?>">
            <h4><?= get_the_title($post_id); ?></h4>
            <span><?= esc_html(CFS()->get('titulo_original', $post_id)); ?></span>
          </a>
        </td>
        <td><?= render_terms('distribuicao', $post_id); ?></td>
        <td>
          <?php $diretores = CFS()->get('direcao', $post_id); ?>
          <?php if ($diretores) : ?>
          <?php 
                          $nomes_diretores = array();
                          if (is_array($diretores)) {
                              foreach ($diretores as $diretor) {
                                  if (is_array($diretor) && isset($diretor['nome'])) {
                                      $nomes_diretores[] = esc_html($diretor['nome']);
                                  } elseif (is_string($diretor)) {
                                      $nomes_diretores[] = esc_html($diretor);
                                  }
                              }
                              echo implode(', ', $nomes_diretores);
                          } elseif (is_string($diretores)) {
                              echo esc_html($diretores);
                          }
                          ?>
          <?php endif; ?>
        </td>
        <td><?= render_terms('paises', $post_id); ?></td>
        <td><?= render_terms('generos', $post_id); ?></td>
        <td><?= CFS()->get('duracao_minutos', $post_id); ?> min</td>
        <td>
          <?php $elenco = CFS()->get('elenco', $post_id); ?>
          <?php if ($elenco) : ?>
          <?php 
                          $nomes_elenco = array();
                          if (is_array($elenco)) {
                              foreach ($elenco as $ator) {
                                  if (is_array($ator) && isset($ator['nome'])) {
                                      $nomes_elenco[] = esc_html($ator['nome']);
                                  } elseif (is_string($ator)) {
                                      $nomes_elenco[] = esc_html($ator);
                                  }
                              }
                              echo implode(', ', $nomes_elenco);
                          } elseif (is_string($elenco)) {
                              echo esc_html($elenco);
                          }
                          ?>
          <?php endif; ?>
        </td>
      </tr>
      <?php
      }

      function render_filmes_tabela($filmes_agrupados, $dias_semana, $has_filters = false, $mostrar_todos = false) {
          if ($mostrar_todos) {
              echo '<table class="tabela-completa">';
              echo '<thead><tr>
                  <th colspan="2" style="width: 20%;">Título</th>
                  <th>Distribuição</th>
                  <th>Direção</th>
                  <th>País</th>
                  <th>Gênero</th>
                  <th style="width: 5%;">Duração</th>
                  <th>Elenco</th>
              </tr></thead>';
              echo '<tbody>';
              
              foreach ($filmes_agrupados['todos_ordenados'] as $post_id => $data_estreia) {
                  render_linha_tabela($post_id);
              }
              
              echo '</tbody></table>';
          } else {
              // Semana atual
              if (!empty($filmes_agrupados['semana_atual'])) {
                  echo '<h2 class="section-title">Filmes desta semana</h2>';
                  foreach ($filmes_agrupados['semana_atual'] as $data => $filmes_ids) {
                      render_dia_tabela($data, $filmes_ids, $dias_semana);
                  }
              }

              // Próxima semana
              if (!empty($filmes_agrupados['proxima_semana'])) {
                  echo '<h2 class="section-title">Próxima semana</h2>';
                  foreach ($filmes_agrupados['proxima_semana'] as $data => $filmes_ids) {
                      render_dia_tabela($data, $filmes_ids, $dias_semana);
                  }
              }

              // 
              if (!empty($filmes_agrupados['semanas_seguintes'])) {
                  echo '<h2 class="section-title">Semanas seguintes</h2>';
                  foreach ($filmes_agrupados['semanas_seguintes'] as $data => $filmes_ids) {
                      render_dia_tabela($data, $filmes_ids, $dias_semana);
                  }
              }

              // Filmes sem data
              if (!empty($filmes_agrupados['sem_data'])) {
                  echo '<h2 class="section-title">Filmes sem data definida</h2>';
                  echo '<table>';
                  echo '<thead><tr>
                      <th colspan="2" style="width: 20%;">Título</th>
                      <th>Distribuição</th>
                      <th>Direção</th>
                      <th>País</th>
                      <th>Gênero</th>
                      <th style="width: 5%;">Duração</th>
                      <th>Elenco</th>
                  </tr></thead>';
                  echo '<tbody>';
                  foreach ($filmes_agrupados['sem_data'] as $post_id) {
                      render_linha_tabela($post_id);
                  }
                  echo '</tbody></table>';
              }

              // Semanas que já passaram
              if (!empty($filmes_agrupados['semanas_passadas'])) {
                  echo '<h2 class="section-title">Lançamentos recentes</h2>';
                  $semanas_passadas = array_slice($filmes_agrupados['semanas_passadas'], 0, 3, true);
                  foreach ($semanas_passadas as $data => $filmes_ids) {
                      render_dia_tabela($data, $filmes_ids, $dias_semana);
                  }
              }
          }
          
          // Mensagem quando não há resultados com filtros
          if ($has_filters && empty($filmes_agrupados['semana_atual']) && 
              empty($filmes_agrupados['proxima_semana']) && 
              empty($filmes_agrupados['semanas_seguintes']) && 
              empty($filmes_agrupados['sem_data']) && 
              empty($filmes_agrupados['semanas_passadas']) &&
              empty($filmes_agrupados['todos_ordenados'])) {
              echo '<div class="no-results">';
              echo '<p>Nenhum filme encontrado com os filtros selecionados.</p>';
              echo '</div>';
          }
      }
      ?>

      <?php
      $banner_lateral = CFS()->get('banner_lateral', $banner_id);
      if (esc_html($banner_lateral) == '1') : ?>
      <div class="grid-lateral">
        <div>
          <section class="area-filmes" v-if="ativoItem === 'lista'">
            <div class="lista-filmes" id="lista">
              <?php render_filmes_lista($filmes_agrupados, $dias_semana, $has_filters, $mostrar_todos); ?>
            </div>
          </section>
          <section class="tabela-filme" v-if="ativoItem === 'tabela'">
            <?php render_filmes_tabela($filmes_agrupados, $dias_semana, $has_filters, $mostrar_todos); ?>
          </section>
        </div>
        <aside>
          <a href="<?php echo esc_url($link_skyscraper); ?>">
            <img src="<?php echo esc_url($skyscraper); ?>">
          </a>
          <a href="<?php echo esc_url($link_big_stampr); ?>">
            <img src="<?php echo esc_url($big_stamp); ?>">
          </a>
        </aside>
      </div>
      <?php else: ?>
      <section class="area-filmes" v-if="ativoItem === 'lista'">
        <div class="lista-filmes" id="lista">
          <?php render_filmes_lista($filmes_agrupados, $dias_semana, $has_filters, $mostrar_todos); ?>
        </div>
      </section>
      <section class="tabela-filme" v-if="ativoItem === 'tabela'">
        <a href="<?php echo esc_url($link_banner_moldura_casado); ?>">
          <img src="<?php echo esc_url($banner_moldura_casado); ?>">
        </a>
        <?php render_filmes_tabela($filmes_agrupados, $dias_semana, $has_filters, $mostrar_todos); ?>
      </section>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
<script src="https://cdn.jsdelivr.net/npm/vue@2.7.16/dist/vue.js"></script>

<script>
new Vue({
  el: "#app",
  data: {
    ativoItem: 'lista',
    hasFilters: <?php echo $has_filters ? 'true' : 'false'; ?>,
    selectedFilters: {
      ano: '<?php echo $selected_ano; ?>',
      mes: '<?php echo $selected_mes; ?>',
      origem: '<?php echo isset($_GET['origem']) ? $_GET['origem'] : ''; ?>',
      distribuicao: '<?php echo isset($_GET['distribuicao']) ? $_GET['distribuicao'] : ''; ?>',
      genero: '<?php echo isset($_GET['genero']) ? $_GET['genero'] : ''; ?>',
      tecnologia: '<?php echo isset($_GET['tecnologia']) ? $_GET['tecnologia'] : ''; ?>'
    }
  },
  methods: {
    setTabAtivo(tab) {
      this.ativoItem = tab;
    },
    resetFilters() {
      this.selectedFilters = {
        ano: '',
        mes: '',
        origem: '',
        distribuicao: '',
        genero: '',
        tecnologia: ''
      };
      window.location.href = '<?php echo get_site_url(); ?>/filmes/';
    },
    hoverCard(e) {
      const cards = this.$el.querySelectorAll(".card");

      cards.forEach((card) => {
        const rect = card.getBoundingClientRect();
        const mouseX = ((e.clientX - rect.left) / rect.width) * 100;
        const mouseY = ((e.clientY - rect.top) / rect.height) * 100;

        const cardInfo = card.querySelector(".info");
        if (cardInfo) {
          cardInfo.style.position = 'absolute';
          cardInfo.style.left = `${mouseX}%`;
          cardInfo.style.top = `${mouseY}%`;
        }
      });
    }
  }
});
</script>
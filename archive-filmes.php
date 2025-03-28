<?php get_header(); ?>

<?php
$current_page_slug = basename(get_permalink());
$category_slug = str_replace('boletim/', '', $current_page_slug);
$banner_id = "77483";
$author_id = get_the_author_meta('ID');

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);

$query = new WP_Query($args);

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

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
  'post_type' => 'filmes',
  'posts_per_page' => 10,
  'post_status' => 'publish',
  'paged' => $paged,
);

// Aplicar filtros
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

$filmes = new WP_Query($args);

// Array para armazenar filmes agrupados por dia
$filmes_por_dia = array();

if ($filmes->have_posts()) {
  while ($filmes->have_posts()) {
    $filmes->the_post();
    $estreia = CFS()->get('estreia');
    if (!empty($estreia)) {
      $data_estreia = DateTime::createFromFormat('Y-m-d', $estreia);
      $data_formatada = $data_estreia->format('Y-m-d');

      if (!isset($filmes_por_dia[$data_formatada])) {
        $filmes_por_dia[$data_formatada] = array();
      }
      $filmes_por_dia[$data_formatada][] = get_post();
    }
  }
  wp_reset_postdata();
}

function render_terms($field_key, $post_id)
{
  $distribuicao = CFS()->get($field_key, $post_id);
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
?>

<?php if ($query->have_posts()): ?>
<?php while ($query->have_posts()): $query->the_post(); ?>
<?php
    $banner_lateral = CFS()->get('banner_lateral', $banner_id);

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

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
<?php endif; ?>

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
          <button aria-label="imprimir" @click="window.print()"><i class="bi bi-printer"></i></button>
        </div>
        <div></div>
        <!-- <section id="datas" class="splide">
          <div class="splide__track">
            <ul class="splide__list">
              <?php
              $datas_filmes = array();
              if ($filmes->have_posts()) {
                while ($filmes->have_posts()) {
                  $filmes->the_post();
                  $estreia = CFS()->get('estreia');
                  if (!empty($estreia)) {
                    $data_estreia = DateTime::createFromFormat('Y-m-d', $estreia);
                    $data_formatada = $data_estreia->format('Y-m-d');
                    if (!in_array($data_formatada, $datas_filmes)) {
                      $datas_filmes[] = $data_formatada;
                    }
                  }
                }
                wp_reset_postdata();
              }

              foreach ($datas_filmes as $data) {
                $data_estreia = DateTime::createFromFormat('Y-m-d', $data);
                $dia_semana_ingles = $data_estreia->format('l');
                $dia_semana = $dias_semana[$dia_semana_ingles]; // Traduz o dia da semana para português
                $dia = $data_estreia->format('d');
                $mes = $data_estreia->format('F');
                $ano = $data_estreia->format('Y');
                echo '<li class="splide__slide" data-date="' . esc_attr($data) . '">' . esc_html($dia_semana) . ', ' . esc_html($dia) . ' de ' . esc_html($mes) . ' de ' . esc_html($ano) . '</li>';
              }
              ?>
            </ul>
          </div>
        </section> -->
        <div class="lancamento">
          <a href="<?php echo get_site_url(); ?>/lancamentos-por-distribuidora/" id="distribuidora">Ver lançamentos por
            distribuidora</a>
        </div>
      </div>
      <section class="grid-select">
        <form method="GET" action="<?php echo get_site_url(); ?>/filmes/">
          <div class="grid grid-7-xl gap-22 select-itens">
            <select id="ano" name="ano" v-model="selectedFilters.ano">
              <option disabled selected value="">Ano</option>
              <option v-for="ano in anos" :value="ano" <?php selected($_GET['ano'], $key); ?>>{{ano}}</option>
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
      <?php

      if (esc_html($banner_lateral) == '1') : ?>
      <div class="grid-lateral">
        <div>
          <section class="area-filmes" v-if="ativoItem === 'lista'">
            <div class="lista-filmes" id="lista">

            </div>
          </section>
          <section class="tabela-filme" v-if="ativoItem === 'tabela'">

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
          <?php render_filmes_lista($filmes_por_dia, $dias_semana); ?>
        </div>
      </section>
      <section class="tabela-filme" v-if="ativoItem === 'tabela'">
        <a href="<?php echo esc_url($link_banner_moldura_casado); ?>">
          <img src="<?php echo esc_url($banner_moldura_casado); ?>">
        </a>
        <?php render_filmes_tabela($filmes_por_dia, $dias_semana); ?>
      </section>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
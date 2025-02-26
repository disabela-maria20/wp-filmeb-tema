<?php get_header(); ?>

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

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
  'post_type' => 'filmes',
  'posts_per_page' => 40,
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

// Array para armazenar filmes agrupados por mês
$filmes_por_mes = array();

if ($filmes->have_posts()) {
    while ($filmes->have_posts()) {
        $filmes->the_post();

        // Obter a data de estreia do filme
        $estreia = CFS()->get('estreia');
        if (!empty($estreia)) {
            $data_estreia = DateTime::createFromFormat('Y-m-d', $estreia);
            $mes = $data_estreia->format('m');
            $ano = $data_estreia->format('Y');

            // Verificar se o ano é o atual ou o selecionado no filtro
            $ano_selecionado = isset($_GET['ano']) ? $_GET['ano'] : date('Y');
            if ($ano == $ano_selecionado) {
                // Agrupar filmes por mês
                if (!isset($filmes_por_mes[$mes])) {
                    $filmes_por_mes[$mes] = array();
                }
                $filmes_por_mes[$mes][] = get_post();
            }
        }
    }
    wp_reset_postdata();
}

function render_terms($field_key, $post_id) {
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
    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $full_banner = CFS()->get('full_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
    $super_banner = CFS()->get('super_banner', $banner_id);
    ?>
<img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
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
      <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner bannerDesktop" alt="banner">
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </div>
  </div>
</section>

<div class="container page-filmes">
  <div id="app">
    <div class="page-filmes">
      <h1>Estreias</h1>
      <div class="grid-filtros-config">
        <div class="ordem">
          <button aria-label="ordem 1" @click="setTabAtivo('lista')"><i class="bi bi-border-all"></i></button>
          <button aria-label="ordem 2" @click="setTabAtivo('tabela')"><i class="bi bi-grid-1x2"></i></button>
          <button aria-label="imprimir" @click="window.print()"><i class="bi bi-printer"></i></button>
        </div>
        <section id="datas" class="splide">
          <div class="splide__track">
            <ul class="splide__list">
              <li class="splide__slide">Quinta-feira, 13/06/2024</li>
              <li class="splide__slide">Quinta-feira, 13/06/2024</li>
              <li class="splide__slide">Quinta-feira, 13/06/2024</li>
            </ul>
          </div>
        </section>
        <div class="lancamento">
          <a href="<?php echo get_site_url(); ?>/lancamentos-por-distribuidora/" id="distribuidora">Ver lançamentos por
            distribuidora</a>
        </div>
      </div>
      <section class="grid-select">
        <form method="GET" action="http://localhost/FilmeB/filmes/">
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
      <section class="area-filmes" v-if="ativoItem === 'lista'">
        <div class="lista-filmes" id="lista">
          <?php if (!empty($filmes_por_mes)): ?>
          <?php ksort($filmes_por_mes);?>
          <?php foreach ($filmes_por_mes as $mes => $filmes): ?>
          <h2> <i class="bi bi-calendar-check-fill"></i> <?php echo esc_html($meses[$mes]); ?></h2>
          <div class="grid-filmes">
            <?php foreach ($filmes as $filme): ?>
            <a v-on:mousemove="hoverCard" href="<?php echo get_permalink($filme->ID); ?>" class="card">
              <?php if (esc_url(CFS()->get('cartaz', $filme->ID)) == ''): ?>
              <h3><?php echo get_the_title($filme->ID); ?></h3>
              <p class="indisponivel">Poster não disponível</p>
              <?php else: ?>
              <img src="<?php echo esc_url(CFS()->get('cartaz', $filme->ID)); ?>"
                alt="<?php echo get_the_title($filme->ID); ?>">
              <?php endif; ?>
              <div class="info">
                <ul>
                  <li><span>Título:</span><strong><?php echo get_the_title($filme->ID); ?></strong></li>
                  <?php if (render_terms('distribuicao', $filme->ID)): ?>
                  <li><span>Distribuição:</span><strong><?php echo render_terms('distribuicao', $filme->ID); ?></strong>
                  </li>
                  <?php endif; ?>
                  <?php if (render_terms('paises', $filme->ID)): ?>
                  <li><span>País:</span><strong><?php echo render_terms('paises', $filme->ID); ?></strong></li>
                  <?php endif; ?>
                  <?php if (render_terms('generos', $filme->ID)): ?>
                  <li><span>Gênero(s)</span><strong><?php echo render_terms('generos', $filme->ID); ?></strong></li>
                  <?php endif; ?>
                  <?php if (CFS()->get('direcao', $filme->ID) !== ''): ?>
                  <li><span>Direção</span><strong><?php echo CFS()->get('direcao'); ?></strong></li>
                  <?php endif; ?>
                  <?php if (CFS()->get('duracao_minutos', $filme->ID) !== '0'): ?>
                  <li><span>Duração</span><strong><?php echo CFS()->get('duracao_minutos', $filme->ID); ?>min</strong>
                  </li>
                  <?php endif; ?>
                </ul>
              </div>
            </a>
            <?php endforeach; ?>
          </div>
          <?php endforeach; ?>
          <?php else: ?>
          <p>Nenhum filme encontrado.</p>
          <?php endif; ?>
        </div>
      </section>
      <section class="tabela-filme" v-if="ativoItem === 'tabela'">
        <?php if (!empty($filmes_por_mes)): ?>
        <?php ksort($filmes_por_mes); ?>
        <?php foreach ($filmes_por_mes as $mes => $filmes): ?>
        <h2> <i class="bi bi-calendar-check-fill"></i><?php echo esc_html($meses[$mes]); ?></h2>
        <table>
          <thead>
            <tr>
              <th colspan="2">Título</th>
              <th>Distribuição</th>
              <th>Direção</th>
              <th>País</th>
              <th>Gênero</th>
              <th>Duração</th>
              <th>Elenco</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($filmes as $filme): ?>
            <tr>
              <td class="titulo" colspan="2">
                <a href="<?php echo get_permalink($filme->ID); ?>">
                  <h3><?php echo get_the_title($filme->ID); ?></h3>
                  <span><?php echo esc_html(CFS()->get('titulo_original', $filme->ID)); ?></span>
                </a>
              </td>
              <td><?php echo render_terms('distribuicao', $filme->ID); ?></td>
              <td><?php echo esc_html(CFS()->get('direcao', $filme->ID)) ?></td>
              <td><?php echo render_terms('paises', $filme->ID); ?></td>
              <td><?php echo render_terms('generos', $filme->ID); ?></td>
              <td><?php echo CFS()->get('duracao_minutos', $filme->ID); ?> min</td>
              <td><?php echo CFS()->get('elenco', $filme->ID); ?></td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
        <?php endforeach; ?>
        <?php else: ?>
        <p>Nenhum filme encontrado.</p>
        <?php endif; ?>
      </section>
      <div class="pagination">
        <?php echo paginate_links(array(
          'total'     => $filmes->max_num_pages,
          'current'   => max(1, get_query_var('paged')),
          'type'      => 'list',
          'prev_text' => __('<'),
          'next_text' => __('>'),
          'mid_size'  => 2,
        )); ?>
      </div>
    </div>
  </div>
</div>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<script src="https://cdn.jsdelivr.net/npm/vue@2/dist/vue.js"></script>
<script>
new Vue({
  el: "#app",
  data: {
    teste: "teste",
    ativoItem: 'lista',
    filmes: [],
    anos: [],
    selectedFilters: {
      ano: '',
      mes: '',
      origem: '',
      distribuidor: '',
      genero: '',
      tecnologia: ''
    },
    filteredMovies: [],
    loading: false,
  },
  methods: {
    async getListaAnos() {
      this.loading = true;

      try {
        const res = await fetch(`<?php echo get_site_url(); ?>/wp-json/api/v1/ano-filmes`);
        if (!res.ok) throw new Error(`Erro na requisição: ${res.status} - ${res.statusText}`);
        const data = await res.json();

        this.anos = data;
      } catch (error) {
        console.error("Erro ao buscar anos:", error);
      } finally {
        this.loading = false;
      }
    },

    setTabAtivo(tab) {
      this.ativoItem = tab;
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
    },
  },
  created() {
    this.getListaAnos();
  },
});
</script>
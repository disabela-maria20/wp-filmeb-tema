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
  'December' => 'Dezembro',
];

$args = array(
  'post_type' => 'filmes',
  'posts_per_page' => -1,
  'post_status' => 'publish'
);
$filmes = new WP_Query($args);

function render_terms($field_key)
{
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

if ($query->have_posts()):
  while ($query->have_posts()):
    $query->the_post();

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

<div class="container page-filmes">

  <div id="app">
    <div class="container page-filmes">
      <h1>Cine-semana</h1>

      <div class="grid-filtros-config">
        <div class="ordem">
          <button aria-label="ordem 1" @click="setTabAtivo('lista')"><i class="bi bi-border-all"></i></button>
          <button aria-label="ordem 2" @click="setTabAtivo('tabela')"><i class="bi bi-grid-1x2"></i></button>
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
          <a href="<?php echo get_site_url(); ?>/lancamentos-por-distribuidora/" id="distribuidora">Ver lançamentos por
            distribuidora</a>
        </div>
      </div>
      <section class="grid-select">
        <div class="grid grid-7-xl gap-22 select-itens">
          <select id="ano" v-model="selectedFilters.ano">
            <option disabled value="">Ano</option>
            <option v-for="ano in anos" :value="ano">{{ano}}</option>
          </select>

          <select v-model="selectedFilters.mes" id="mes">
            <option disabled value="">Mês</option>
            <?php foreach ($meses as $key => $value) { ?>
            <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
            <?php } ?>
          </select>
          <select v-model="selectedFilters.origem" id="origem">
            <option disabled value="">Origem</option>
            <?php foreach ($paises as $paise) { ?>
            <option value="<?php echo esc_html($paise->name); ?>"><?php echo $paise->name . PHP_EOL; ?>
            </option>
            <?php } ?>
          </select>
          <select v-model="selectedFilters.distribuidor" id="distribuidor">
            <option disabled value="">Distribuidor</option>
            <?php foreach ($distribuidoras as $distribuidora) { ?>
            <option value="<?php echo esc_html($distribuidora->name); ?>"><?php echo $distribuidora->name . PHP_EOL; ?>
            </option>
            <?php } ?>
          </select>
          <select v-model="selectedFilters.genero" id="genero">
            <option disabled value="">Gênero</option>
            <?php foreach ($termos as $termo) { ?>
            <option value="<?php echo esc_html($termo->name); ?>"><?php echo $termo->name . PHP_EOL; ?></option>
            <?php } ?>
          </select>
          <select v-model="selectedFilters.tecnologia" id="tecnologia">
            <option disabled value="">Tecnologia</option>
            <?php foreach ($tecnologias as $tecnologia) { ?>
            <option value="<?php echo esc_html($tecnologia->name); ?>"><?php echo $tecnologia->name . PHP_EOL; ?>
            </option>
            <?php } ?>
          </select>
        </div>
      </section>
      <div v-if="loading" class="loading">
        Carregando filmes...
      </div>
      <div v-else>
        <section class="area-filmes">
          <div class="lista-filmes" v-if="ativoItem === 'lista'" id="lista">
            <div class="grid-filmes">
              <div v-for="(filme, index) in FiltrarFilme" :key="index">
                <a class="card" v-on:mousemove="hoverCard" :href="filme.link" :key="index" ref="cards">
                  <div v-if="!filme.cartaz">
                    <h3>{{filme.title}}</h3>
                    <p class="indisponivel">Poster não disponível</p>
                  </div>
                  <div v-else>
                    <img :src="filme.cartaz" alt="<?php the_title(); ?>" class="poster">
                  </div>

                  <div class="info">
                    <ul>
                      <li> <span>Título:</span> <strong>{{filme.title}}</strong> </li>
                      <li>
                        <span>Distribuição:</span>
                        <div>
                          <strong v-for="(value, index) in filme.distribuidoras" :key="index">{{value}}</strong>
                        </div>
                      </li>
                      <li>
                        <span>País:</span>
                        <div>
                          <strong v-for="(value, index) in filme.paises" :key="index">{{value}}</strong>
                        </div>
                      </li>
                      <li>
                        <span>Gênero:</span>
                        <div>
                          <strong v-for="(value, index) in filme.generos" :key="index">{{value}}</strong>
                        </div>
                      </li>
                      <li> <span>Direção:</span> <strong>{{filme.direcao}}</strong></li>
                      <li> <span>Duração</span> <strong>{{filme.duracao_minutos}}min</strong></li>
                    </ul>
                  </div>
                </a>
              </div>
            </div>
          </div>

        </section>
        <div class="tabela-filme" v-if="ativoItem === 'tabela'" id="tabela">
          <div :key="index">
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
                </tr>
              </thead>
              <tbody>
                <tr v-for="(filme, index) in FiltrarFilme">
                  <td class="titulo">
                    <a :href="filme.link">
                      <h3>{{filme.title}}</h3>
                      <span>{{filme.titulo_original}}</span>
                    </a>
                  </td>
                  <td>
                    <div v-for="(value, index) in filme.distribuidoras" :key="index">{{value}}</div>
                  </td>
                  <td>{{filme.direcao}}</td>
                  <td>
                    <div v-for="(value, index) in filme.paises" :key="index">{{value}}</div>
                  </td>
                  <td>
                    <div v-for="(value, index) in filme.generos" :key="index">
                      {{value}}
                    </div>
                  </td>
                  <td>{{filme.duracao_minutos}}min</td>
                  <td>{{filme.elenco}}</td>


                </tr>
              </tbody>
            </table>
          </div>
        </div>
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
    async getListaFilmes() {
      this.loading = true;

      try {
        const res = await fetch(`<?php echo get_site_url(); ?>/wp-json/api/v1/filmes`);
        if (!res.ok) throw new Error(`Erro na requisição: ${res.status} - ${res.statusText}`);
        const data = await res.json();

        console.log("Filmes carregados:", data);
        this.filmes = data;

      } catch (error) {
        console.error("Erro ao buscar filmes:", error);
      } finally {
        this.loading = false;
      }
    },
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

    traduzirMesParaPortugues(mesIngles) {
      const mesesIngles = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
      ];

      const mesesPortugues = [
        "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
        "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
      ];

      const index = mesesIngles.indexOf(mesIngles.charAt(0).toUpperCase() + mesIngles.slice(1));

      if (index !== -1) {
        return mesesPortugues[index];
      } else {
        return "Mês inválido";
      }
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
  computed: {
    FiltrarFilme() {
      const filmes = this.filmes
        .filter(filme => {
          return (
            (this.selectedFilters.ano ? filme.ano.toString() === this.selectedFilters.ano : true) &&
            (this.selectedFilters.mes ? filme.mes === this.selectedFilters.mes : true) &&
            (this.selectedFilters.origem ? filme.origem === this.selectedFilters.origem : true) &&
            (this.selectedFilters.distribuidor ? filme.distribuidor === this.selectedFilters.distribuidor :
              true) &&
            (this.selectedFilters.genero ? filme.genero === this.selectedFilters.genero : true) &&
            (this.selectedFilters.tecnologia ? filme.tecnologia === this.selectedFilters.tecnologia : true)
          );
        });
      return filmes;
    }
  },
  created() {
    this.getListaAnos();
    this.getListaFilmes();
  },
});
</script>
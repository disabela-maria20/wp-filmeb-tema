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
          <a href="/lancamentos-por-distribuidora/" id="distribuidora">Ver lançamentos por
            distribuidora</a>
        </div>
      </div>
      <section class="grid-select">
        <div class="grid grid-7-xl gap-22 select-itens">
          <select id="ano" v-model="selectedFilters.ano" @change="getListaFilmes">
            <option disabled value="">Ano</option>
            <option v-for="ano in anos" :value="ano" :key="ano">{{ ano }}</option>
          </select>

          <select v-model="selectedFilters.mes" id="mes" @change="getListaFilmes">
            <option disabled value="">Mês</option>
            <?php foreach ($meses as $key => $value) { ?>
              <option value="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></option>
            <?php } ?>
          </select>

          <select v-model="selectedFilters.origem" id="origem" @change="getListaFilmes">
            <option disabled value="">Origem</option>
            <?php foreach ($paises as $paise) { ?>
              <option value="<?php echo esc_html($paise->name); ?>"><?php echo esc_html($paise->name); ?></option>
            <?php } ?>
          </select>

          <select v-model="selectedFilters.distribuidor" id="distribuidor" @change="getListaFilmes">
            <option disabled value="">Distribuidor</option>
            <?php foreach ($distribuidoras as $distribuidora) { ?>
              <option value="<?php echo esc_html($distribuidora->name); ?>"><?php echo esc_html($distribuidora->name); ?>
              </option>
            <?php } ?>
          </select>

          <select v-model="selectedFilters.genero" id="genero" @change="getListaFilmes">
            <option disabled value="">Gênero</option>
            <?php foreach ($termos as $termo) { ?>
              <option value="<?php echo esc_html($termo->name); ?>"><?php echo esc_html($termo->name); ?></option>
            <?php } ?>
          </select>

          <select v-model="selectedFilters.tecnologia" id="tecnologia" @change="getListaFilmes">
            <option disabled value="">Tecnologia</option>
            <?php foreach ($tecnologias as $tecnologia) { ?>
              <option value="<?php echo esc_html($tecnologia->name); ?>"><?php echo esc_html($tecnologia->name); ?>
              </option>
            <?php } ?>
          </select>
        </div>
      </section>
      <section class="area-filmes">
        <!-- <div v-for="(filme, index) in FiltrarFilme" :key="index">
          <div v-for="card in filme.months">
            <div class="lista-filmes" v-if="ativoItem === 'lista'" id="lista">
              <h2>
                <i class="bi bi-calendar-check-fill"></i>
                <span>{{traduzirMesParaPortugues(card.month)}}</span>
              </h2>
              <div class="grid-filmes">
                <div v-for="item in card.movies">
                  <a class="card" v-on:mousemove="hoverCard" :href="item.link">
                    <div v-if="!item.cartaz">
                      <h3>{{item.title}}</h3>
                      <p class="indisponivel">Poster não disponível</p>
                    </div>
                    <div v-else>
                      <img :src="item.cartaz" alt="<?php the_title(); ?>" class="poster">
                    </div>

                    <div class="info">
                      <ul>
                        <li> <span>Título:</span> <strong>{{item.title}}</strong> </li>
                        <li>
                          <span>Distribuição:</span>
                          <div>
                            <strong v-for="(value, index) in item.distribuidoras" :key="index">{{value}}</strong>
                          </div>
                        </li>
                        <li>
                          <span>País:</span>
                          <div>
                            <strong v-for="(value, index) in item.paises" :key="index">{{value}}</strong>
                          </div>
                        </li>
                        <li>
                          <span>Gênero:</span>
                          <div>
                            <strong v-for="(value, index) in item.generos" :key="index">{{value}}</strong>
                          </div>
                        </li>
                        <li> <span>Direção:</span> <strong>{{item.direcao}}</strong></li>
                        <li> <span>Duração</span> <strong>{{item.duracao_minutos}}min</strong></li>
                      </ul>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>

        </div> -->

        <div class="tabela-distribuidora" v-if="ativoItem === 'tableDistribuidora'" id="tableDistribuidora">
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
      <div class="tabela-filme" v-if="ativoItem === 'tabela'" id="tabela">
        <!-- <div v-for="(filme, index) in FiltrarFilme" :key="index">
          <div v-for="card in filme.months">
            <h2>
              <i class="bi bi-calendar-check-fill"></i>
              <span>{{traduzirMesParaPortugues(card.month)}}</span>
            </h2>
            <table v-for="item in card.movies">
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
                <tr>
                  <td class="titulo">
                    <a :href="item.link">
                      <h3>{{item.title}}</h3>
                      <span>{{item.titulo_original}}</span>
                    </a>
                  </td>
                  <td>
                    <div v-for="(value, index) in item.distribuidoras" :key="index">{{value}}</div>
                  </td>
                  <td>{{item.direcao}}</td>
                  <td v-for="(value, index) in item.paises" :key="index">{{value}}</td>
                  <td v-for="(value, index) in item.generos" :key="index">{{value}}</td>
                  <td>{{item.duracao_minutos}}min</td>
                  <td>{{item.elenco}}</td>
                  <td v-for="(value, index) in item.classificacoes" :key="index">{{value}}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div> -->
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
      ativoItem: 'lista',
      filmes: [],
      anos: [],
      currentPage: 1, // Página atual
      totalPages: 0, // Total de páginas
      totalFilmes: 0, // Total de filmes
      limit: 20, // Número de filmes por página
      selectedFilters: {
        q: "",
        ano: '',
        mes: '',
        origem: '',
        distribuidor: '',
        genero: '',
        tecnologia: ''
      },
      filteredMovies: [],
    },
    methods: {
      async getListaFilmes() {
        console.log(this.selectedFilters.mes);

        try {
          const query = new URLSearchParams({
            q: this.selectedFilters.q,
            ano: this.selectedFilters.ano,
            distribuicao: this.selectedFilters.distribuidor,
            generos: this.selectedFilters.genero,
            tecnologias: this.selectedFilters.tecnologia,
            page: this.currentPage,
            limit: this.limit,
          }).toString();

          const res = await fetch(`http://localhost/FilmeB/wp-json/api/v1/filmes?${query}`);
          if (!res.ok) throw new Error("Erro ao buscar filmes");
          const data = await res.json();

          this.filmes = data.filmes || data; // Verifique a estrutura dos dados retornados
          this.totalFilmes = res.headers.get("X-Total-Count") || data.total || 0;
          this.totalPages = Math.ceil(this.totalFilmes / this.limit);
        } catch (error) {
          console.error("Erro ao buscar filmes:", error);
        }
      },

      async getListaAnos() {
        try {
          const res = await fetch(`http://localhost/FilmeB/wp-json/api/v1/anos-filmes`);
          if (!res.ok) throw new Error(`Erro na requisição: ${res.status} - ${res.statusText}`);
          const data = await res.json();

          this.anos = data;
        } catch (error) {
          console.error("Erro ao buscar anos:", error);
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
      }
    },
    computed: {

    },
    watch: {
      'selectedFilters.ano': {
        handler() {
          this.getListaFilmes();
        },
        immediate: true, // Garante que o watcher execute na criação do componente
      }
    },
    created() {
      const anoAtual = new Date().getFullYear().toString();
      this.selectedFilters.ano = anoAtual;

      this.getListaAnos();
      this.getListaFilmes();
    },
  });
</script>
<?php
get_header();
// Template Name: Distribuidora
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

<div class="container page-filmes">

  <div id="app">
    <div class="container page-filmes">
      <h1>Lançamentos por Distribuidora</h1>
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
            <?php foreach ($paises as $paise) {?>
            <option value="<?php echo esc_html($paise->name); ?>"><?php echo $paise->name . PHP_EOL;?>
            </option>
            <?php } ?>
          </select>
          <select v-model="selectedFilters.distribuidor" id="distribuidor">
            <option disabled value="">Distribuidor</option>
            <?php foreach ($distribuidoras as $distribuidora) {?>
            <option value="<?php echo esc_html($distribuidora->name); ?>"><?php echo $distribuidora->name . PHP_EOL;?>
            </option>
            <?php } ?>
          </select>
          <select v-model="selectedFilters.genero" id="genero">
            <option disabled value="">Gênero</option>
            <?php foreach ($termos as $termo) {?>
            <option value="<?php echo esc_html($termo->name); ?>"><?php echo $termo->name . PHP_EOL;?></option>
            <?php } ?>
          </select>
          <select v-model="selectedFilters.tecnologia" id="tecnologia">
            <option disabled value="">Tecnologia</option>
            <?php foreach ($tecnologias as $tecnologia) {?>
            <option value="<?php echo esc_html($tecnologia->name); ?>"><?php echo $tecnologia->name . PHP_EOL;?>
            </option>
            <?php } ?>
          </select>
        </div>
      </section>
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
    filteredMovies: []
  },
  methods: {
    async getLitsaFilmes(ano = this.selectedFilters.ano) {
      try {
        const res = await fetch(`http://filme-b.local/wp-json/api/v1/filmes?ano=${ano}`);
        if (!res.ok) throw new Error(`Erro na requisição: ${res.status} - ${res.statusText}`);
        const data = await res.json();

        this.filmes = data;
      } catch (error) {
        console.error("Erro ao buscar filmes:", error);
      }
    },

    async getListaAnos() {
      try {
        const res = await fetch('http://filme-b.local/wp-json/api/v1/anos-filmes');
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
      const cards = this.$el.querySelectorAll(".card"); // Selecionando todos os cards

      cards.forEach((card) => {
        const rect = card.getBoundingClientRect(); // Obtendo o retângulo do card
        const mouseX = ((e.clientX - rect.left) / rect.width) * 100;
        const mouseY = ((e.clientY - rect.top) / rect.height) * 100;

        const cardInfo = card.querySelector(".info"); // Selecionando a info dentro de cada card
        if (cardInfo) {
          cardInfo.style.position = 'absolute';
          cardInfo.style.left = `${mouseX}%`;
          cardInfo.style.top = `${mouseY}%`;
        }
      });
    }
  },
  computed: {
    FiltrarFilme() {
      return this.filmes.filter((filme) => {
        //  ano
        const filtroAno = this.selectedFilters.ano ?
          filme.year === this.selectedFilters.ano :
          true;

        //  meses
        const filtroMes = this.selectedFilters.mes ?
          filme.months && filme.months.some((mes) => mes.month === this.selectedFilters.mes) :
          true;

        // Paises
        const filtroOrigem = this.selectedFilters.origem ?
          filme.months &&
          filme.months.flatMap((mes) =>
            mes.movies.filter((movie) => movie.paises.includes(this.selectedFilters.origem))
          ).length > 0 :
          true;

        //Distribuidor
        const filtroDistribuidor = this.selectedFilters.distribuidor ?
          filme.months?.some((mes) =>
            mes.movies.some((movie) => movie.distribuidoras.includes(this.selectedFilters.distribuidor))
          ) :
          true;

        //Genero
        const filtroGenero = this.selectedFilters.genero ?
          filme.months?.some((mes) =>
            mes.movies.some((movie) => movie.generos.includes(this.selectedFilters.genero))
          ) :
          true;

        //Tecnologia
        const filtroTecnologia = this.selectedFilters.tecnologia ?
          filme.months?.some((mes) =>
            mes.movies.some((movie) => movie.tecnologias.includes(this.selectedFilters.tecnologia))
          ) :
          true;


        return filtroAno && filtroMes && filtroOrigem && filtroDistribuidor && filtroGenero && filtroTecnologia;
      });
    },
  },
  created() {
    const anoAtual = new Date().getFullYear().toString();
    this.selectedFilters.ano = anoAtual;

    this.getListaAnos();
    this.getLitsaFilmes(anoAtual);
  },
});
</script>
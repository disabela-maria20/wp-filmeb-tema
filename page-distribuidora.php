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

<div id="app">
  <div class="container page-distribuidora">
    <h1>Lançamentos por Distribuidora</h1>
    <section class="grid-select">
      <div class="grid grid-7-xl gap-22 select-itens">
        <select id="ano" v-model="selectedFilters.ano">
          <option value="">Ano</option>
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
    <div v-else class="tabela-distribuidora" id="tableDistribuidora">
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
          <tr v-for="(filme, index) in FiltrarFilme" :key="index">
            <td>
              <span class="data" v-html="formatarData(filme.estreia)"></span>
            </td>
            <td>
              <div v-for="(value, index) in filme.Disney" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.Paramount" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.Sony" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.Universal" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.Warner" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.Diamond" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.downtownParis" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.Imagem" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.Paris" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
            <td>
              <div v-for="(value, index) in filme.OutrasDistribuidoras" :key="index">
                <div>
                  <h3>{{value.title}}</h3>
                  <h4>{{value.titulo_original}}</h4>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="pagination">
        <button :disabled="paginaAtual === 1" @click="navegarParaPagina(1)">
          <i class="bi bi-chevron-left"></i>
        </button>

        <button v-for="n in totalPaginas" :key="n" :class="{ active: n === paginaAtual }" @click="navegarParaPagina(n)">
          {{ n }}
        </button>

        <button :disabled="paginaAtual === totalPaginas" @click="navegarParaPagina(totalPaginas)">
          <i class="bi bi-chevron-right"></i>
        </button>
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
    filmes: [], // Lista completa de filmes
    anos: [], // Lista de anos disponíveis
    selectedFilters: {
      ano: '',
      mes: '',
      origem: '',
      distribuidor: '',
      genero: '',
      tecnologia: ''
    },
    paginaAtual: 1,
    totalPaginas: 1,
    filmesPorPagina: 50,
    loading: false
  },
  methods: {
    // Carrega a lista completa de filmes
    async carregarFilmes() {
      try {
        this.loading = true;
        const url =
          `<?php echo get_site_url(); ?>/wp-json/api/v1/distribuidoras?limit=1000`; // Carrega todos os filmes de uma vez
        const res = await fetch(url);
        if (!res.ok) throw new Error(`Erro na requisição: ${res.status} - ${res.statusText}`);
        const data = await res.json();
        console.log("Dados carregados da API:", data); // Log para depuração
        this.filmes = data.data || []; // Garante que filmes seja um array
        this.totalPaginas = Math.ceil(this.filmes.length / this.filmesPorPagina); // Calcula o total de páginas
      } catch (error) {
        console.error("Erro ao buscar filmes:", error);
      } finally {
        this.loading = false;
      }
    },

    // Carrega a lista de anos disponíveis
    async carregarAnos() {
      try {
        const res = await fetch(`<?php echo get_site_url(); ?>/wp-json/api/v1/ano-filmes`);
        if (!res.ok) throw new Error(`Erro na requisição: ${res.status} - ${res.statusText}`);
        const data = await res.json();
        this.anos = data;
      } catch (error) {
        console.error("Erro ao buscar anos:", error);
      }
    },

    // Formata a data para exibição
    formatarData(data) {
      const date = new Date(data);
      const dia = date.getDate();
      const mes = date.getMonth() + 1;
      const ano = date.getFullYear();
      const diaSemana = date.getDay();
      const meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro',
        'Outubro', 'Novembro', 'Dezembro'
      ];
      const diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira',
        'Sábado'
      ];
      return `
        <div>
          <div class="dia">${String(dia).padStart(2, '0')}</div>
          <div class="mes">${meses[mes - 1]}</div>
          <div class="ano">${ano}</div>
          <div class="semana">${diasSemana[diaSemana]}</div>
        </div>
      `;
    },

    // Navega para uma página específica
    navegarParaPagina(pagina) {
      this.paginaAtual = pagina;
    }
  },
  computed: {
    // Filtra e ordena os filmes com base nos filtros selecionados
    FiltrarFilme() {
      let filtered = this.filmes;

      console.log("Filmes antes da filtragem:", filtered); // Log para depuração
      console.log("Filtros aplicados:", this.selectedFilters); // Log para depuração

      // Aplica os filtros
      if (this.selectedFilters.ano) {
        filtered = filtered.filter(filme => filme.ano == this.selectedFilters
          .ano); // Use == para comparar strings/numbers
      }
      if (this.selectedFilters.mes) {
        filtered = filtered.filter(filme => filme.mes.toLowerCase() === this.selectedFilters.mes
          .toLowerCase()); // Ignora maiúsculas/minúsculas
      }
      if (this.selectedFilters.origem) {
        filtered = filtered.filter(filme => filme.origem === this.selectedFilters.origem);
      }
      if (this.selectedFilters.distribuidor) {
        filtered = filtered.filter(filme => filme.distribuidoras && filme.distribuidoras.includes(this
          .selectedFilters.distribuidor));
      }
      if (this.selectedFilters.genero) {
        filtered = filtered.filter(filme => filme.genero && filme.genero.includes(this.selectedFilters.genero));
      }
      if (this.selectedFilters.tecnologia) {
        filtered = filtered.filter(filme => filme.tecnologia && filme.tecnologia.includes(this.selectedFilters
          .tecnologia));
      }

      console.log("Filmes após filtragem:", filtered); // Log para depuração

      // Ordena os filmes por data de estreia (do mais recente para o mais antigo)
      filtered.sort((a, b) => new Date(b.estreia) - new Date(a.estreia));

      // Paginação
      const inicio = (this.paginaAtual - 1) * this.filmesPorPagina;
      const fim = inicio + this.filmesPorPagina;
      return filtered.slice(inicio, fim);
    }
  },
  created() {
    // Define o ano e mês atuais como filtros padrão
    const dataAtual = new Date();
    this.selectedFilters.ano = dataAtual.getFullYear().toString();
    this.selectedFilters.mes = dataAtual.toLocaleString('default', {
      month: 'long'
    });

    // Carrega os dados iniciais
    this.carregarAnos();
    this.carregarFilmes();
  }
});
</script>
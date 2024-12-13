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

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<div class="container page-filmes">
  <h1>Cine-semana</h1>

  <div class="grid-filtros-config">
    <div class="ordem">
      <button aria-label="ordem 1"><i class="bi bi-border-all"></i></button>
      <button aria-label="ordem 2"><i class="bi bi-grid-1x2"></i></button>
      <button aria-label="imprimir"><i class="bi bi-printer"></i></button>
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
      <button id="distribuidora">Ver lançamentos por distribuidora</button>
    </div>
  </div>
  <section class="grid-select">
    <div class="grid grid-7-xl gap-22">
      <select name="ano" id="ano">
        <option value="">Ano</option>
      </select>
      <select name="mes" id="mes">
        <option value="">Mês</option>
      </select>
      <select name="origem" id="origem">
        <option value="">Origem</option>
      </select>
      <select name="distribuidor" id="distribuidor">
        <option value="">Distribuidor</option>
      </select>
      <select name="genero" id="genero">
        <option value="">Gênero</option>
      </select>
      <select name="tecnologia" id="tecnologia">
        <option value="">Tecnologia</option>
      </select>
      <button>Filtrar</button>
    </div>
  </section>
  <section class="area-filmes">
    <div class="lista-filmes" id="lista">
      <h2> <i class="bi bi-calendar-check-fill"></i> Quinta-feira, 13/06/2024</h2>
      <div class="grid-filmes">
        <div class="card">
          <h3>nome do filme</h3>
          <p class="indisponivel">Poster não disponível</p>
          <img src="" alt="" class="poster">

          <div class="info">
            <ul>
              <li>Título: <strong>Grande Sertão</strong> </li>
              <li>Distribuição: <strong>Downtown / Paris </strong></li>
              <li>Direção <strong>Guel Arraes</strong></li>
              <li>País: <strong>Brasil</strong></li>
              <li>Gênero: <strong>Drama</strong></li>
            </ul>
          </div>
        </div>
        <div class="card">
          <h3>nome do filme</h3>
          <p class="indisponivel">Poster não disponível</p>
          <img src="" alt="" class="poster">

          <div class="info">
            <ul>
              <li>Título: <strong>Grande Sertão</strong> </li>
              <li>Distribuição: <strong>Downtown / Paris </strong></li>
              <li>Direção <strong>Guel Arraes</strong></li>
              <li>País: <strong>Brasil</strong></li>
              <li>Gênero: <strong>Drama</strong></li>
            </ul>
          </div>
        </div>
        <div class="card">
          <h3>nome do filme</h3>
          <p class="indisponivel">Poster não disponível</p>
          <img src="" alt="" class="poster">

          <div class="info">
            <ul>
              <li>Título: <strong>Grande Sertão</strong> </li>
              <li>Distribuição: <strong>Downtown / Paris </strong></li>
              <li>Direção <strong>Guel Arraes</strong></li>
              <li>País: <strong>Brasil</strong></li>
              <li>Gênero: <strong>Drama</strong></li>
            </ul>
          </div>
        </div>
        <div class="card">
          <h3>nome do filme</h3>
          <p class="indisponivel">Poster não disponível</p>
          <img src="" alt="" class="poster">

          <div class="info">
            <ul>
              <li>Título: <strong>Grande Sertão</strong> </li>
              <li>Distribuição: <strong>Downtown / Paris </strong></li>
              <li>Direção <strong>Guel Arraes</strong></li>
              <li>País: <strong>Brasil</strong></li>
              <li>Gênero: <strong>Drama</strong></li>
            </ul>
          </div>
        </div>
        <div class="card">
          <h3>nome do filme</h3>
          <p class="indisponivel">Poster não disponível</p>
          <img src="" alt="" class="poster">

          <div class="info">
            <ul>
              <li>Título: <strong>Grande Sertão</strong> </li>
              <li>Distribuição: <strong>Downtown / Paris </strong></li>
              <li>Direção <strong>Guel Arraes</strong></li>
              <li>País: <strong>Brasil</strong></li>
              <li>Gênero: <strong>Drama</strong></li>
            </ul>
          </div>
        </div>
        <div class="card">
          <h3>nome do filme</h3>
          <p class="indisponivel">Poster não disponível</p>
          <img src="" alt="" class="poster">

          <div class="info">
            <ul>
              <li>Título: <strong>Grande Sertão</strong> </li>
              <li>Distribuição: <strong>Downtown / Paris </strong></li>
              <li>Direção <strong>Guel Arraes</strong></li>
              <li>País: <strong>Brasil</strong></li>
              <li>Gênero: <strong>Drama</strong></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="tabela-filme" id="tabela">
      <h2> <i class="bi bi-calendar-check-fill"></i> Quinta-feira, 13/06/2024</h2>
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
            <th>Classificação</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="titulo">
              Os observadores
              <span>The watchers</span>
            </td>
            <td>Warner</td>
            <td>Ishana Night
              Shyamalan</td>
            <td>Estados Unidos</td>
            <td>Terror</td>
            <td>102min</td>
            <td>Dakota Fanning, Georgina Campbell</td>
            <td>14 anos</td>
          </tr>
          <tr>
            <td class="titulo">
              Os observadores
              <span>The watchers</span>
            </td>
            <td>Warner</td>
            <td>Ishana Night
              Shyamalan</td>
            <td>Estados Unidos</td>
            <td>Terror</td>
            <td>102min</td>
            <td>Dakota Fanning, Georgina Campbell</td>
            <td>14 anos</td>
          </tr>
          <tr>
            <td class="titulo">
              Os observadores
              <span>The watchers</span>
            </td>
            <td>Warner</td>
            <td>Ishana Night
              Shyamalan</td>
            <td>Estados Unidos</td>
            <td>Terror</td>
            <td>102min</td>
            <td>Dakota Fanning, Georgina Campbell</td>
            <td>14 anos</td>
          </tr>
        </tbody>
      </table>
    </div>
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
  </section>

</div>

<?php endwhile;
endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
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
    <button>Ver lançamentos por distribuidora</button>
  </div>
  <section class="grid-select">
    <div>
      <select name="ano" id="ano">
        Ano
      </select>
      <select name="mes" id="mes">
        Mês
      </select>
      <select name="origem" id="origem">
        Origem
      </select>
      <select name="distribuidor" id="distribuidor">
        distribuidor
      </select>
      <select name="genero" id="genero">
        Gênero
      </select>
      <select name="tecnologia" id="tecnologia">
        Tecnologia
      </select>
      <button>Filtrar</button>
    </div>
  </section>
  <section class="area-filmes">
    <div class="lista-filmes">
      <h2>Quinta-feira, 13/06/2024</h2>
      <div class="grid-filmes">
        <div>
          <div>
            <h3>nome do filme</h3>
            <p>Poster não disponível</p>

            <img src="" alt="" class="poster">
          </div>
          <div>
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

  </section>

</div>

<?php endwhile;
endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
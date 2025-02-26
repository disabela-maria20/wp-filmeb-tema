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

<?php 

$termos = get_terms(array(
  'taxonomy'   => 'Gêneros',
  'hide_empty' => false, 
));

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

?>
<img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile" alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <!-- <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner" alt="banner"> -->
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
      <!-- <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner bannerDesktop" alt="banner"> -->
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </div>
  </div>
</section>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<section class="page-filmes-aberta">
  <div class="container">
    <div class="banner" style=" background-image: url('<?php echo esc_url(CFS()->get('capa')); ?>')">
      <h1>
        <strong>
          <?php the_title(); ?>
        </strong>
        <span>
          <?php echo CFS()->get('titulo_original') ?>
        </span>
      </h1>
    </div>
    <h2>Ficha Técnica</h2>
    <div class="grid-filmes">
      <div class="item">
        <img src="<?php echo esc_url(CFS()->get('cartaz')); ?>" class="cartaz">
        <div class="area-poster">
          <div class="dados">
            <h3>Estreia</h3>
            <?php
              $estreia = CFS()->get('estreia');

              if (!empty($estreia)) {
                  $data_formatada = date_i18n('j \d\e F \d\e Y', strtotime($estreia));
                  echo '<p>' . esc_html($data_formatada) . '</p>';
              }
              ?>
          </div>
          <div class="dados">
            <h3>Trailer</h3>
            <div class="video">
              <iframe width="560" height="315" src="<?php echo esc_url(CFS()->get('trailer')); ?>"
                title="YouTube video player" frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            </div>

          </div>
          <div class="dados">
            <h3>Fotos</h3>
            <section id="fotos" class="splide">
              <div class="splide__track">
                <ul class="splide__list">

                  <?php $fields = CFS()->get('fotos'); ?>
                  <?php if ($fields) { ?>
                  <?php foreach ($fields as $field) { ?>
                  <li class="splide__slide">
                    <img src="<?php echo esc_html($field['foto']); ?>" class="img-slide" alt="">
                  </li>
                  <?php } ?>
                  <?php } ?>
                </ul>
              </div>
            </section>
            <section id="modalFilme" class="modal">
              <div class="modal-body">
                <button class="close" aria-label="Fechar">
                  <i class="bi bi-x"></i>
                </button>
                <div class="modal-img"></div>
              </div>
            </section>
          </div>
        </div>
      </div>
      <div>
        <div class="grid grid-2-lg">
          <div class="dados">
            <table>
              <tr>
                <td class="titulo">Distribuição</td>
                <td>
                  <?php echo render_terms('distribuicao')?>
                </td>
              </tr>
              <tr>
                <td class="titulo">País</td>
                <td>
                  <?php echo render_terms('paises')?>
                </td>
              </tr>
              <tr>
                <td class="titulo">Gênero</td>
                <td>
                  <?php echo render_terms('generos')?>
                </td>
              </tr>
            </table>
          </div>
          <div class="dados">
            <table>
              <tr>
                <td class="titulo">Duração</td>
                <td>
                  <?php echo CFS()->get('duracao_minutos'); ?>min
                </td>
              </tr>
              <tr>
                <td class="titulo">Tecnologia</td>
                <td>
                  <?php echo render_terms('tecnologias')?>
                </td>
              </tr>
              <tr>
                <td class="titulo">Classificação</td>
                <td>
                  <?php echo render_terms('classificacao')?>
                </td>
              </tr>
            </table>
          </div>
        </div>
        <div class="dados">
          <table>
            <tr>
              <td class="titulo">Sinopse</td>
              <td>
                <?php the_content(); ?>
              </td>
            </tr>
          </table>
        </div>
        <div class="dados">
          <h3>Direção</h3>
          <p>
            <?php echo CFS()->get('direcao'); ?>
          </p>
        </div>
        <div class="dados">
          <h3>Roteiro</h3>
          <p>
            <?php echo CFS()->get('roteiro'); ?>
          </p>
        </div>
        <div class="dados">
          <h3>Elenco</h3>
          <p>
            <?php echo CFS()->get('elenco'); ?>
          </p>
        </div>
      </div>
    </div>
  </div>

</section>

<?php endwhile;
endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Inicializar o Splide
  var splide = new Splide('#fotos', {
    arrows: true,
    perPage: 3,
    gap: '30px',
    pagination: false,
  });
  splide.mount();

  const imgs = document.querySelectorAll('.img-slide');
  const modal = document.querySelector('#modalFilme');
  const modalBody = document.querySelector('.modal-img');
  const close = document.querySelector('.close');

  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });


  imgs.forEach((img) => {
    img.addEventListener('click', () => {
      modalBody.innerHTML = `<img src="${img.src}" alt="">`
      modal.style.display = 'flex';
      modal.style.alignItems = 'center'
      modal.style.justifyContent = 'center'
    });
  });

  close.addEventListener('click', () => {
    modal.style.display = 'none';
  });
});
</script>
<?php
get_header();

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

function is_cfs_field_empty($field_key) {
  $field_value = CFS()->get($field_key);
  return empty($field_value);
}

function has_valid_items($array) {
  if (!is_array($array)) return false;
  
  foreach ($array as $item) {
    if (is_array($item) && !empty($item['nome'])) {
      return true;
    }
  }
  return false;
}

$banner_id = "78919";
$banner_superior = CFS()->get('banner_moldura', $banner_id);
$banner_inferior = CFS()->get('mega_banner', $banner_id);

$link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
$link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);

?>
<a href="<?php echo esc_url($link_banner_superior); ?>" target="_blank" rel="noopener noreferrer">
  <img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </a>
  </div>
</div>

<?php
get_template_part('components/MenuMobile/index');
get_template_part('components/MenuDesktop/index');
?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
    </div>
  </div>
</section>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<section class="page-filmes-aberta">
  <div class="container">
    <div class="banner"
      style="background-image: url('<?php echo esc_url(CFS()->get('capa')); ?>'); background-color: #4b4b4b; background-attachment: fixed;">
      <h1>
        <strong><?php the_title(); ?></strong>
        <span><?php echo CFS()->get('titulo_original'); ?></span>
      </h1>
    </div>

    <h2>Ficha técnica</h2>
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
            <?php if (CFS()->get('trailer') !== 'http://NULL') : ?>
            <h3>Trailer</h3>
            <div class="video">
              <?php echo better_youtube_embed_block_render_block(['url' => CFS()->get('trailer')]); ?>
            </div>
            <?php endif; ?>
          </div>
          <div class="dados">
            <?php if (!is_cfs_field_empty('fotos')) : ?>
            <h3>Fotos</h3>
            <section id="fotos" class="splide">
              <div class="splide__track">
                <ul class="splide__list">
                  <?php $fields = CFS()->get('fotos'); ?>
                  <?php if ($fields) : ?>
                  <?php foreach ($fields as $field) : ?>
                  <li class="splide__slide">
                    <img src="<?php echo esc_url($field['foto']); ?>" class="img-slide" alt="">
                  </li>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </ul>
              </div>
            </section>
            <section id="modalFilme" class="modal">
              <div class="modal-body">
                <button class="close" aria-label="Fechar">
                  <i class="bi bi-x"></i>
                </button>
                <div class="modal-img"></div>
                <div class="arrow">
                  <button>
                    <i class="bi bi-chevron-left"></i>
                  </button>
                  <button>
                    <i class="bi bi-chevron-right"></i>
                  </button>
                </div>
              </div>
            </section>
            <?php endif; ?>
          </div>
        </div>
      </div>

      <div>
        <div class="grid grid-2-lg">
          <div class="dados">
            <table>
              <?php if (!is_cfs_field_empty('distribuicao')) : ?>
              <tr>
                <td class="titulo-fixa">Distribuição</td>
                <td><?php echo render_terms('distribuicao'); ?></td>
              </tr>
              <?php endif; ?>

              <?php if (!is_cfs_field_empty('paises')) : ?>
              <tr>
                <td class="titulo-fixa">País</td>
                <td><?php echo render_terms('paises'); ?></td>
              </tr>
              <?php endif; ?>

              <?php if (!is_cfs_field_empty('generos')) : ?>
              <tr>
                <td class="titulo-fixa">Gênero</td>
                <td><?php echo render_terms('generos'); ?></td>
              </tr>
              <?php endif; ?>
            </table>
          </div>
          <div class="dados">
            <table>
              <?php if (!is_cfs_field_empty('duracao_minutos')) : ?>
              <tr>
                <td class="titulo-fixa">Duração</td>
                <td><?php echo CFS()->get('duracao_minutos'); ?> &nbsp;min</td>
              </tr>
              <?php endif; ?>

              <?php if (!is_cfs_field_empty('tecnologias')) : ?>
              <tr>
                <td class="titulo-fixa">Tecnologia</td>
                <td><?php echo render_terms('tecnologias'); ?></td>
              </tr>
              <?php endif; ?>

              <?php if (!is_cfs_field_empty('classificacao')) : ?>
              <tr>
                <td class="titulo-fixa">Classificação</td>
                <td><?php echo render_terms('classificacao'); ?></td>
              </tr>
              <?php endif; ?>
            </table>
          </div>
        </div>
        <div class="dados">
          <table>
            <tr>
              <td class="titulo">Sinopse</td>
              <td id="sinopse"><?php the_content(); ?></td>
            </tr>
          </table>
        </div>

        <?php // Seção de Direção ?>
        <?php if (!is_cfs_field_empty('direcao')) : ?>
        <?php $diretores = CFS()->get('direcao'); ?>
        <?php if (is_array($diretores) && has_valid_items($diretores)) : ?>
        <div class="dados">
          <h3>Direção</h3>
          <div class="info-array">
            <?php foreach ($diretores as $diretor) : ?>
            <?php if (is_array($diretor) && !empty($diretor['nome'])) : ?>
            <div>
              <?php if (!empty($diretor['foto'])) : ?>
              <img src="<?php echo esc_url($diretor['foto']); ?>" alt="<?php echo esc_attr($diretor['nome']); ?>">
              <?php endif; ?>
              <p><?php echo esc_html($diretor['nome']); ?></p>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <?php // Seção de Roteiro ?>
        <?php if (!is_cfs_field_empty('roteiro')) : ?>
        <?php $roteiristas = CFS()->get('roteiro'); ?>
        <?php if (is_array($roteiristas) && has_valid_items($roteiristas)) : ?>
        <div class="dados">
          <h3>Roteiro</h3>
          <div class="info-array">
            <?php foreach ($roteiristas as $roteirista) : ?>
            <?php if (is_array($roteirista) && !empty($roteirista['nome'])) : ?>
            <div>
              <?php if (!empty($roteirista['foto'])) : ?>
              <img src="<?php echo esc_url($roteirista['foto']); ?>" alt="<?php echo esc_attr($roteirista['nome']); ?>">
              <?php endif; ?>
              <p><?php echo esc_html($roteirista['nome']); ?></p>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>

        <?php // Seção de Elenco ?>
        <?php if (!is_cfs_field_empty('elenco')) : ?>
        <?php $elenco = CFS()->get('elenco'); ?>
        <?php if (is_array($elenco) && has_valid_items($elenco)) : ?>
        <div class="dados">
          <h3>Elenco</h3>
          <div class="info-array">
            <?php foreach ($elenco as $ator) : ?>
            <?php if (is_array($ator) && !empty($ator['nome'])) : ?>
            <div>
              <?php if (!empty($ator['foto'])) : ?>
              <img src="<?php echo esc_url($ator['foto']); ?>" alt="<?php echo esc_attr($ator['nome']); ?>">
              <?php endif; ?>
              <p><?php echo esc_html($ator['nome']); ?></p>
            </div>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
</section>

<?php
  endwhile;
endif;

get_template_part('components/Footer/index');
get_footer();
?>

<script>
document.addEventListener('DOMContentLoaded', function() {
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
  let currentIndex = 0;

  function openModal(index) {
    currentIndex = index;
    modalBody.innerHTML = `<img src="${imgs[currentIndex].src}" alt="">`;
    modal.style.display = 'flex';
    modal.style.alignItems = 'center';
    modal.style.justifyContent = 'center';
  }

  function nextPhoto() {
    currentIndex = (currentIndex + 1) % imgs.length;
    modalBody.innerHTML = `<img src="${imgs[currentIndex].src}" alt="">`;
  }

  function prevPhoto() {
    currentIndex = (currentIndex - 1 + imgs.length) % imgs.length;
    modalBody.innerHTML = `<img src="${imgs[currentIndex].src}" alt="">`;
  }

  imgs.forEach((img, index) => {
    img.addEventListener('click', () => {
      openModal(index);
    });
  });

  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
    }
  });

  close.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  const prevButton = document.querySelector('.arrow button:first-child');
  const nextButton = document.querySelector('.arrow button:last-child');

  if (prevButton && nextButton) {
    prevButton.addEventListener('click', (e) => {
      e.stopPropagation();
      prevPhoto();
    });

    nextButton.addEventListener('click', (e) => {
      e.stopPropagation();
      nextPhoto();
    });
  }

  document.addEventListener('keydown', (e) => {
    if (modal.style.display === 'flex') {
      if (e.key === 'ArrowRight') {
        nextPhoto();
      } else if (e.key === 'ArrowLeft') {
        prevPhoto();
      }
    }
  });
});

const sinopse = document.querySelector('#sinopse p');

if (sinopse && (sinopse.textContent.trim() === "NULL" || sinopse.textContent.trim() === "")) {
  sinopse.innerHTML = "Sinopse não disponível";
}
</script>
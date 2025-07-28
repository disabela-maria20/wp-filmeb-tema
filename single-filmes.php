<?php
get_header();

function render_terms($field_key) {
  $distribuicao = CFS()->get($field_key);
  $output = '';
  if (!empty($distribuicao)) {
    foreach ($distribuicao as $term_id) {
      $term = get_term($term_id);
      if ($term && !is_wp_error($term)) {
        $output .= '<p>' . esc_html($term->name) . '</p>';
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

?>
<div class="w-full p-35 img-banner bannerMobile">
  <?php echo do_shortcode('[bm_banner id="399779"]');?>
</div>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <?php echo do_shortcode('[bm_banner id="399761"]');?>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <?php echo do_shortcode('[bm_banner id="399761"]');?>
    </div>
  </div>
</section>
</section>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<section class="page-filmes-aberta">
  <div class="container">
    <h1>
      <strong><?php the_title(); ?></strong>
      <span><?php echo CFS()->get('titulo_original'); ?></span>
    </h1>
    <h2>
      <?php
       // [sem_data] => 1 [mes] => Array ( [Julho] => Julho ) [ano] => 2025 
        $sem_data = CFS()->get('sem_data');
   
        $estreia = CFS()->get('estreia');
        if (!empty($estreia)) {
          $dia_semana = date_i18n('l', strtotime($estreia));
          $data_formatada = date_i18n('j \d\e F \d\e Y', strtotime($estreia));
          echo '<p class="semana">' . 
            ($sem_data == 1 ? '<span class="alterado">' . esc_html($data_formatada) . '</span>' : esc_html($data_formatada)) . 
            ', ' . esc_html($dia_semana) . 
            '</p>';
        }
      ?>
    </h2>
    <div class="grid-filmes <?php echo  ($banner_lateral == '1' ? 'grid-publi' : '')?>">
      <div>
        <img src="<?php echo esc_url(CFS()->get('cartaz')); ?>" class="cartaz">
        <?php if (CFS()->get('trailer') !== 'http://NULL') : ?>
        <h3>Trailer</h3>
        <div class="video">
          <?php echo better_youtube_embed_block_render_block(['url' => CFS()->get('trailer')]); ?>
        </div>
        <?php endif; ?>
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
      <div>
        <div class="fichaTecnica">
          <div class="grid-fixa-tecnica">
            <?php if (!is_cfs_field_empty('distribuicao')) : ?>
            <h3>Distribuição</h3>
            <?php endif; ?>
            <div><?php echo render_terms('distribuicao'); ?></div>
          </div>
          <div class="grid-fixa-tecnica">
            <?php if (!is_cfs_field_empty('paises')) : ?>
            <h3>País</h3>
            <?php endif; ?>
            <div><?php echo render_terms('paises'); ?></div>
          </div>

          <div class="grid-fixa-tecnica">
            <?php if (!is_cfs_field_empty('generos')) : ?>
            <h3>Gênero</h3>
            <?php endif; ?>
            <div><?php echo render_terms('generos'); ?></div>
          </div>
          <?php 
              $duracao = CFS()->get('duracao_minutos');
              if ($duracao && $duracao !== '0') : 
            ?>
          <div class="grid-fixa-tecnica">
            <h3>Duração</h3>
            <p><?php echo esc_html($duracao); ?>&nbsp;min</p>
          </div>
          <?php endif; ?>
          <div class="grid-fixa-tecnica">
            <?php if (!is_cfs_field_empty('classificacao')) : ?>
            <h3>Classificação</h3>
            <?php endif; ?>
            <?php echo render_terms('classificacao'); ?>
          </div>
          <div class="grid-fixa-tecnica">
            <?php if (!is_cfs_field_empty('tecnologias')) : ?>
            <h3>Tecnologia</h3>
            <?php endif; ?>
            <div><?php echo render_terms('tecnologias'); ?></div>
          </div>
          <div class="grid-fixa-tecnica">
            <div>
              <h3>Sinopse</h3>
            </div>
            <div id="sinopse">
              <?php the_content(); ?>
            </div>

          </div>

        </div>
        <?php // Seção de Direção ?>
        <?php if (!is_cfs_field_empty('direcao')) : ?>
        <?php $diretores = CFS()->get('direcao'); ?>
        <?php if (is_array($diretores) && has_valid_items($diretores)) : ?>
        <div class="grid-fixa-tecnica">
          <h3>Direção</h3>
          <div>
            <?php foreach ($diretores as $diretor) : ?>
            <?php if (is_array($diretor) && !empty($diretor['nome'])) : ?>
            <p><?php echo esc_html($diretor['nome']); ?></p>
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
        <div class="grid-fixa-tecnica">
          <h3>Roteiro</h3>
          <div>
            <?php foreach ($roteiristas as $roteirista) : ?>
            <?php if (is_array($roteirista) && !empty($roteirista['nome'])) : ?>
            <p><?php echo esc_html($roteirista['nome']); ?></p>
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
        <div class="grid-fixa-tecnica">
          <h3>Elenco</h3>
          <div>
            <?php foreach ($elenco as $ator) : ?>
            <?php if (is_array($ator) && !empty($ator['nome'])) : ?>
            <p><?php echo esc_html($ator['nome']); ?></p>
            <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
        <?php endif; ?>
      </div>
      <?php
      
      if (esc_html($banner_lateral) == '1') : ?>
      <div class="<?php echo ($banner_lateral == '1' ? 'aside-info' : ''); ?>">
        <aside>
          <a href="<?php echo esc_url($link_skyscraper); ?>">
            <img src="<?php echo esc_url($skyscraper); ?>">
          </a>
          <a href="<?php echo esc_url($link_big_stampr); ?>">
            <img src="<?php echo esc_url($big_stamp); ?>">
          </a>
        </aside>
      </div>
      <?php endif; ?>
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
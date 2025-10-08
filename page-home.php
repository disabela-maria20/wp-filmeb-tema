<?php
// Template Name: Home


get_header();
$filme = get_thursday_movies();

$filmesCount = ($filme instanceof WP_Query) ? $filme->post_count : 0;
// error_reporting(E_ALL);
// ini_set('display_errors', 1);


$banner_lateral = CFS()->get('banner_lateral');
$banner_lateral_estreia = CFS()->get('banner_lateral_estreia');

$video = CFS()->get('video');



$recent_posts_query = new WP_Query(array(
  'post_type' => 'post',
  'posts_per_page' => 8,
  'orderby' => 'date',
  'order' => 'DESC',
  'category__not_in' => 'Rapidinhas',
));

$recent_posts_query_banner = new WP_Query(array(
  'post_type' => 'post',
  'posts_per_page' => 5,
  'orderby' => 'date',
  'order' => 'DESC'
));


?>


<div class="w-full p-35 img-banner bannerMobile">
  <?php echo do_shortcode('[bm_banner id="399745"]');?>
</div>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <?php echo do_shortcode('[bm_banner id="400027"]');?>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <?php echo do_shortcode('[bm_banner id="400027"]');?>
    </div>
  </div>
</section>

<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>
<div class="container">
  <section class="owl-carousel slide">
    <?php
    $values = CFS()->get('noticia');
    if (!empty($values) && is_array($values)) {
      foreach ($values as $post_id) {
        $the_post = get_post($post_id);

        if ($the_post) {
          $titulo = get_the_title($the_post);
          $link   = get_permalink($the_post);
          $imagem = CFS()->get('imagem', $post_id);
          $data_field = CFS()->get('data', $post_id);
          $descricao  = CFS()->get('descricao', $post_id);
          $chapeu = CFS()->get('chapeu', $post_id);
          ?>

    <div class="item">
      <?php if (!empty($imagem)) { ?>
      <img src="<?php echo esc_url($imagem); ?>" alt="<?php echo esc_attr($titulo); ?>" />
      <?php } ?>

      <div class="info">
        <a href="<?php echo esc_url($link); ?>">
          <?php if ($chapeu) echo  '<span>' . $chapeu . '</span>'; ?>
          <h2><?php echo esc_html($titulo); ?></h2>
          <span class="data">
            <?php
                    if (!empty($data_field)) {
                      $data = strtotime($data_field);
                      echo date('j', $data) . ' ' . mb_substr(strtolower(date_i18n('F', $data)), 0, 3) . ' ' . date('Y', $data);
                    }
                  ?>
          </span> <i>&nbsp;⎸</i>
          <p class="paragrafo">
            <?php
              if (!empty($the_post->post_excerpt)) {
                echo esc_html($the_post->post_excerpt);
              } else {
                echo wp_trim_words(
                  wp_strip_all_tags($the_post->post_content),
                  50, // limite em palavras
                  '...'
                );
              }
              ?>
          </p>
        </a>
      </div>
    </div>

    <?php
        } // fim if $the_post
      } // fim foreach
    } // fim if values
  ?>
  </section>





  <section class="home_table">
    <div class="home_table grid grid-2-lg gap-32">
      <div>

        <div class="area">
          <div class="titulo">
            <h2> Bilheteria fim de semana Brasil </h2>
            <span></span>
          </div>
          <?php echo do_shortcode('[table id=5 /]'); ?>
        </div>

        <span>Fonte: Filme B Box Office</span>
      </div>
      <div>
        <div class="area">
          <div class="titulo">
            <h2>10 maiores bilheterias do ano no Brasil</h2>
            <span></span>
          </div>
          <?php echo do_shortcode('[table id=4 /]'); ?>
        </div>

        <span>Fonte: Filme B Box Office</span>
      </div>
    </div>
  </section>
  <section class="home_newllater">
    <div class="container">
      <?php echo do_shortcode('[bm_banner id="399745"]');?>
    </div>
  </section>
  <section class="home-filmes">
    <h2>Lançamentos da semana</h2>
    <?php if (!empty($banner_lateral_estreia) && esc_html($banner_lateral_estreia) == '1') { ?>
    <div class="grid-filmes">
      <div>
        <section id="filmesHome" class="owl-carousel">
          <?php if ($filme->have_posts()) {
                  while ($filme->have_posts()) {
                    $filme->the_post();
                    $cartaz = CFS()->get('cartaz') ?? '';
                    $titulo = CFS()->get('titulo') ?? get_the_title();
                    ?>
          <div class="item">
            <?php if (esc_url($cartaz) == '') { ?>
            <a href="<?php the_permalink(); ?>" class="card">
              <h3><?php echo get_the_title(); ?></h3>
              <p class="indisponivel">Poster não disponível</p>
            </a>
            <?php } else { ?>
            <a href="<?php the_permalink(); ?>">
              <img src="<?php echo esc_url($cartaz); ?>" alt="<?php echo esc_attr($titulo); ?>" />
              <h3><?php echo get_the_title(); ?></h3>
            </a>
            <?php } ?>
          </div>
          <?php
                  }
                } ?>
        </section>
      </div>
      <div>
        <?php echo do_shortcode('[bm_banner id="399749"]');?>
      </div>
    </div>
    <?php } else { ?>
    <div class="grid-filmes-full">
      <section id="filmesHomeFull" class="owl-carousel">
        <?php if ($filme->have_posts()) {
                while ($filme->have_posts()) {
                  $filme->the_post();
                  $cartaz = CFS()->get('cartaz') ?? '';
                  $titulo = CFS()->get('titulo') ?? get_the_title();
                  ?>
        <div class="item">
          <?php if (esc_url($cartaz) == '') { ?>
          <a href="<?php the_permalink(); ?>" class="card">
            <h3><?php echo get_the_title(); ?></h3>
            <p class="indisponivel">Poster não disponível</p>
          </a>
          <?php } else { ?>
          <a href="<?php the_permalink(); ?>">
            <img src="<?php echo esc_url($cartaz); ?>" alt="<?php echo esc_attr($titulo); ?>" />
            <h3><?php echo get_the_title(); ?></h3>
          </a>
          <?php } ?>
        </div>
        <?php }} ?>
      </section>
    </div>
    <?php } ?>
  </section>
  <section class="home_lista_noticias">
    <h2>Publicações recentes</h2>
    <?php if (!empty($banner_lateral) && esc_html($banner_lateral) == '1') { ?>

    <div class="grid-recentes">
      <div>
        <?php if ($recent_posts_query->have_posts()) {
                while ($recent_posts_query->have_posts()) {
                  $recent_posts_query->the_post();
                  $imagem_url = CFS()->get('imagem');
                  $titulo = CFS()->get('titulo') ?: get_the_title();
                  ?>
        <div class="item">
          <?php if (!empty($imagem_url)): ?>
          <img src="<?php echo esc_url($imagem_url); ?>" alt="<?php echo esc_attr($titulo); ?>" />
          <?php endif; ?>
          <div>
            <a href="<?php the_permalink(); ?>">
              <h3><?php echo esc_html($titulo); ?></h3>
              <span class="data">
                <?php
                          $data_raw = CFS()->get('data');
                          if (!empty($data_raw)) {
                            $data = strtotime($data_raw);
                            echo date('j', $data) . ' ' . mb_substr(strtolower(date_i18n('F', $data)), 0, 3) . ' ' . date('Y', $data);
                          }
                          ?>
              </span>
              <span class="leia-mais">Leia mais</span>
            </a>
          </div>
        </div>

        <?php }
              } ?>
      </div>
      <aside>

        <?php echo do_shortcode('[bm_banner id="399753"]');?>
        <?php if (!empty($video)) { ?>
        <div class="video">
          <iframe width="560" height="315" src="<?php echo esc_url($video); ?>" title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>
        <?php } ?>
      </aside>
    </div>
    <?php } else { ?>
    <div class="grid grid-2-lg gap-32">
      <?php if ($recent_posts_query->have_posts()) {
              while ($recent_posts_query->have_posts()) {
                $recent_posts_query->the_post();

                $imagem_url = CFS()->get('imagem');
                $titulo = CFS()->get('titulo') ?: get_the_title();
                ?>
      <div class="item">
        <?php if (!empty($imagem_url)): ?>
        <img src="<?php echo esc_url($imagem_url); ?>" alt="<?php echo esc_attr($titulo); ?>" />
        <?php endif; ?>
        <div>
          <a href="<?php the_permalink(); ?>">
            <h3><?php echo esc_html($titulo); ?></h3>
            <span class="data">
              <?php
                        $data_raw = CFS()->get('data');
                        if (!empty($data_raw)) {
                          $data = strtotime($data_raw);
                          echo date('j', $data) . ' ' . mb_substr(strtolower(date_i18n('F', $data)), 0, 3) . ' ' . date('Y', $data);
                        }
                        ?>
            </span>
            <span class="leia-mais">Leia mais</span>
          </a>
        </div>
      </div>
      <?php }
            } ?>
    </div>
    <?php } ?>


  </section>
  <section class="home_lista_rapinhas">
    <h2 class="titulo">Rapidinhas</h2>

    <!-- Carousel Mobile -->
    <section class="home_lista_rapinhas bannerMobile">
      <div class="owl-carousel rapidinhas">
        <?php get_template_part('components/RapidinhasMobile/index'); ?>
      </div>
    </section>

    <!-- Grid Desktop -->
    <section class="home_lista_rapinhas bannerDesktop">
      <div class="grid grid-2-md gap-32">
        <?php get_template_part('components/RapidinhasDesktop/index'); ?>
      </div>
    </section>
  </section>
</div>
<?php endwhile;
else:
endif; ?>
<div class="rodape">
  <div class="bg-filmeB">
    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-boletim-filme-b.png" alt="cine B" />

    <div class="container">
      <div class="area_saiba_mais">
        <h2>Conheça o Boletim</h2>
        <p>Acompanhe de perto o mercado de cinema! Assine o Boletim Filme B e receba, toda semana, bilheterias,
          análises e as principais movimentações da indústria no Brasil e no mundo.</p>
        <a href="<?php echo get_site_url(); ?>/quemsomos/">Saiba mais</a>
      </div>
    </div>
  </div>
</div>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>


<script>
// Carrega o Splide plugin

var filmesCount = <?php echo $filmesCount; ?>;

document.addEventListener("DOMContentLoaded", function() {
  jQuery(document).ready(function($) {
    $('#filmesHome').owlCarousel({
      loop: true,
      margin: 10,
      nav: false,
      dots: true,
      mouseDrag: true,
      autoplay: true,
      autoplayTimeout: 6000,
      navText: ["<i class='bi bi-chevron-left'></i>", "<i class='bi bi-chevron-right'></i>"],
      responsive: {
        0: {
          items: 3
        },
        1024: {
          items: 3
        },
      }
    });
    $('#filmesHomeFull').owlCarousel({
      loop: true,
      autoHeight: false,
      margin: 10,
      nav: false,
      dots: true,
      mouseDrag: true,
      autoplay: false,
      autoplayTimeout: 6000,
      navText: [
        "<i class='bi bi-chevron-left'></i>",
        "<i class='bi bi-chevron-right'></i>"
      ],
      responsive: {
        0: {
          items: 3
        },
        992: {
          items: 4
        },
        1200: {
          items: Math.min(8, Math.max(5, filmesCount))
        }
      }
    });


    jQuery(document).ready(function($) {
      $('.slide').owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: false,
        mouseDrag: true,
        autoplay: true,
        autoplayTimeout: 6000,
        navText: ["<i class='bi bi-chevron-left'></i>", "<i class='bi bi-chevron-right'></i>"],
        responsive: {
          0: {
            items: 1
          },
        }
      });
    });
  });
});
</script>
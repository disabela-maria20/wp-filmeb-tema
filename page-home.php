<?php
// Template Name: Home
get_header();

$filme = get_thursday_movies();

$banner_estreia = CFS()->get('banner_estreia');
$banner_lateral = CFS()->get('banner_lateral');
$banner_skyscraper = CFS()->get('banner_skyscraper');

$link_banner_estreia = CFS()->get('link_banner_estreia');

$video = CFS()->get('video');



$recent_posts_query = new WP_Query(array(
  'post_type' => 'post',
  'posts_per_page' => 10,
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

<a href="<?php echo CFS()->get('link_banner_superior'); ?>">
  <img src="<?php echo CFS()->get('banner_superior'); ?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo CFS()->get('link_banner_inferior'); ?>">
      <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
    </a>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="container bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <a href="<?php echo CFS()->get('link_banner_inferior'); ?>">
        <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
      </a>
    </div>
  </div>
</section>

<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>
<div class="container">
  <section class="owl-carousel slide">
    <?php if ($recent_posts_query_banner->have_posts()) {
          while ($recent_posts_query_banner->have_posts()) {
            $recent_posts_query_banner->the_post(); ?>
    <div class="item">
      <?php if (esc_url(CFS()->get('imagem')) != '') {  ?>
      <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
        alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
      <?php } ?>
      <div>
        <span>
          <?php 
            $categoria = get_the_category_list(', ');
            $chapel = CFS()->get('chapeu');
            
            if($categoria === 'plus'){
              echo $chapel;
            } else {
              echo $categoria;
            }?>
        </span>
        <a href="<?php the_permalink(); ?>">
          <h2><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h2>
        </a>
        <p><?php echo esc_html(wp_trim_words(CFS()->get('descricao') ?: get_the_excerpt(), 30, '...')); ?></p>
      </div>
    </div>
    <?php }
          wp_reset_postdata();
        } else {
          echo '<p>Nenhum post encontrado.</p>';
        } ?>
  </section>
  <section class="home_table">
    <div class="home_table grid grid-2-lg gap-32">
      <div>

        <div class="area">
          <div class="titulo">
            <h2> 10 maiores bilheterias do fim de semana no Brasil </h2>
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
      <a href="<?php echo CFS()->get('link_banner_newsllater'); ?>">
        <img src="<?php echo CFS()->get('banner_newsllater'); ?>" class="img-banner d-block m-auto" alt="banner">
      </a>
    </div>
    <div class="grid">
      <div>
        <h2>Receba a nossa newsletter</h2>
        <p>
          Fique por dentro do que movimenta o mercado de cinema! Receba tendências, estreias, bilheterias e os temas
          mais relevantes da indústria diretamente no seu e-mail.</p>
      </div>
      <div class="enviar">
        <input type="text" placeholder="Digite o seu e-mail">
        <button>Enviar</button>
      </div>
    </div>
  </section>
  <section class="home-filmes">
    <h2>Lançamentos da semana</h2>
    <div class="grid-filmes">
      <div>
        <section id="filmesHome" class="owl-carousel">
          <?php if ($filme->have_posts()) {while ($filme->have_posts()) {$filme->the_post(); ?>
          <div class="item">
            <?php if (esc_url(CFS()->get('cartaz')) == '') {  ?>
            <a href="<?php the_permalink(); ?>" class="card">
              <h3><?php echo get_the_title() ?></h3>
              <p class="indisponivel">Poster não disponível</p>
            </a>
            <?php } else { ?>
            <a href="<?php the_permalink(); ?>" class="">
              <img src="<?php echo esc_url(CFS()->get('cartaz')); ?>"
                alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
              <h3><?php echo get_the_title() ?></h3>
            </a>
            <?php } ?>
            </a>
          </div>
          <?php }
              } ?>
        </section>
      </div>
      <div>
        <a href="<?php echo esc_attr($link_banner_estreia); ?>">
          <img class="publi" src="<?php echo esc_html($banner_estreia) ?>" alt="Banner de publicidade" />
        </a>
      </div>
    </div>
  </section>
  <section class="home_lista_noticias">
    <h2>Publicações recentes</h2>
    <?php if (esc_html($banner_lateral) == '1') { ?>
    <div class="grid-recentes">
      <div>
        <?php if ($recent_posts_query->have_posts()) {
                while ($recent_posts_query->have_posts()) {
                  $recent_posts_query->the_post(); ?>
        <div class="item">
          <?php if (esc_url(CFS()->get('imagem')) != '') {  ?>
          <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <?php } ?>

          <div>
            <span><?php echo get_the_category_list(', '); ?></span>
            <a href="<?php the_permalink(); ?>">
              <h3>
                <?php echo esc_html(CFS()->get('titulo') ?: get_the_title());  ?>
              </h3>
            </a>
            <span class="data"> <?php echo date_i18n('j \d\e F \d\e Y', strtotime((CFS()->get('data')))); ?></span>
          </div>
        </div>
        <?php }
              } ?>
      </div>
      <aside>
        <a href="<?php echo esc_url(CFS()->get('link_banner_skyscraper')); ?>">
          <img src="<?php echo esc_url($banner_skyscraper); ?>">
        </a>

        <div class="video">
          <iframe width="560" height="315" src="<?php echo esc_url($video); ?>" title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
        </div>

      </aside>
    </div>
    <?php } else { ?>
    <div class="grid grid-2-lg gap-32">
      <?php if ($recent_posts_query->have_posts()) {
              while ($recent_posts_query->have_posts()) {
                $recent_posts_query->the_post(); ?>
      <div class="item">
        <?php if (esc_url(CFS()->get('imagem')) != '') {  ?>
        <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
          alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
        <?php } ?>
        <div>
          <span><?php echo get_the_category_list(', '); ?></span>
          <a href="<?php the_permalink(); ?>">
            <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3>
          </a>
          <span class="data">
            <?php echo date_i18n('j \d\e F \d\e Y', strtotime((CFS()->get('data')))); ?>
          </span>
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
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>


<script>
// Carrega o Splide plugin
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
          items: 5
        },
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
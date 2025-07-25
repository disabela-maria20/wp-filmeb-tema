<?php
// Template Name: Boletim
get_header();

// Configuração de erros (apenas para desenvolvimento)
// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$edicoes_query = new WP_Query(array(
  'post_type' => 'edicoes',
  'posts_per_page' => 1,
  'orderby' => 'date',     
  'order' => 'DESC',
));

// Tratamento seguro para os banners
$banner_id = "78919";
$banner_superior = CFS()->get('banner_moldura', $banner_id) ?: '';
$banner_inferior = CFS()->get('mega_banner', $banner_id) ?: '';
$full_banner = CFS()->get('full_banner', $banner_id) ?: '';
$skyscraper = CFS()->get('skyscraper', $banner_id) ?: '';

$link_banner_superior = CFS()->get('link_banner_moldura', $banner_id) ?: '#';
$link_banner_inferior = CFS()->get('link_mega_banner', $banner_id) ?: '#';
$link_full_banner = CFS()->get('link_full_banner', $banner_id) ?: '#';
$link_skyscraper = CFS()->get('link_skyscraper', $banner_id) ?: '#';

$big_stamp = CFS()->get('big_stamp', $banner_id);
$link_big_stampr = CFS()->get('link_big_stamp', $banner_id);


$post_id = get_the_ID();

function abreviar_mes($texto) {
    $meses = [
        'janeiro' => 'jan',
        'fevereiro' => 'fev',
        'março' => 'mar',
        'abril' => 'abr',
        'maio' => 'mai',
        'junho' => 'jun',
        'julho' => 'jul',
        'agosto' => 'ago',
        'setembro' => 'set',
        'outubro' => 'out',
        'novembro' => 'nov',
        'dezembro' => 'dez',
    ];

    foreach ($meses as $mesCompleto => $mesAbreviado) {
        // Substitui o nome do mês completo pelo abreviado, respeitando letras minúsculas e acentuação
        $texto = preg_replace('/\b' . preg_quote($mesCompleto, '/') . '\b/i', $mesAbreviado, $texto);
    }

    return $texto;
}

?>

<?php if (!empty($banner_superior)) : ?>
<a href="<?php echo esc_url($link_banner_superior); ?>" target="_blank" rel="noopener noreferrer">
  <img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
</a>
<?php endif; ?>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <?php if (!empty($banner_inferior)) : ?>
    <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </a>
    <?php endif; ?>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <?php if (!empty($banner_inferior)) : ?>
      <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php if (have_posts()): while (have_posts()): the_post(); ?>

<div class="container">
  <div class="grid-list-post-boletim gap-64-md">
    <div>
      <?php if (!empty($full_banner)) : ?>
      <a href="<?php echo esc_url($link_full_banner); ?>">
        <img src="<?php echo esc_url($full_banner); ?>" class="img-banner" style="padding-bottom: 25px;" alt="banner">
      </a>
      <?php endif; ?>

      <div id="breadcrumbs">
        <?php if ( function_exists('bcn_display') ) {
          bcn_display();
      } ?>
      </div>

      <?php if ($edicoes_query->have_posts()): ?>

      <?php while ($edicoes_query->have_posts()): $edicoes_query->the_post(); ?>
      <h2 class="titulo-cinza"><?php echo abreviar_mes(get_the_title()); ?></h2>

      <div class="posts">
        <?php
          $values = CFS()->get('edicao');
          if (!empty($values) && is_array($values)) { 
            $counter = 0;
            foreach ($values as $post_id) { 
              
             
              $the_post = get_post($post_id);
              $post_image = CFS()->get('imagem', $post_id) ?: '';
              $post_title = $the_post->post_title ?: '';
              $post_content = $the_post->post_content ?: '';
              $post_date = $the_post->post_date ?: '';
              $post_url = str_replace("https://filmeb.isabelamribeiro.com.br", get_site_url(), $the_post->guid) ?: '#';
              $counter++;
            ?>
        <div class="post">
          <?php if(!empty($post_image)) : ?>
          <img class="img-post" src="<?php echo esc_url($post_image); ?>" alt="<?php echo esc_attr($post_title); ?>" />
          <?php endif; ?>
          <div>
            <?php if(!empty($post_date)) : ?>
            <a href="<?php echo esc_url($post_url); ?>" class="read-more">
              <h2><?php echo esc_html($post_title); ?></h2>
            </a>
            <span class="data">
              <?php $data=strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>
            </span> <i>&nbsp;⎸</i>
            <p class="paragrafo">
              <?php echo wp_trim_words($post_content ?: get_the_excerpt(), 20, '...'); ?>
            </p>
            <?php endif; ?>
          </div>
        </div>
        <?php } // Fechamento do foreach ?>
        <?php } // Fechamento do if ?>
      </div>
      <?php endwhile; ?>

      <?php else: ?>
      <p>Nenhum boletim encontrado.</p>
      <?php endif; ?>

      <?php wp_reset_postdata(); ?>

      <?php if (!empty($super_banner)) : ?>
      <a href="<?php echo esc_url($link_super_banner); ?>">
        <img src="<?php echo esc_url($super_banner); ?>" class="img-banner" alt="banner">
      </a>
      <?php endif; ?>

      <h3 class="titulo">Rapidinha</h3>
      <!-- Carousel Mobile -->
      <section class="home_lista_rapinhas bannerMobile">
        <div class="owl-carousel rapidinhas">
          <?php get_template_part('components/RapidinhasMobile/index'); ?>
        </div>
      </section>
      <!-- Grid Desktop -->
      <section class="home_lista_rapinhas bannerDesktop">
        <div class="grid gap-32">
          <?php get_template_part('components/RapidinhasDesktop/index'); ?>
        </div>
      </section>
    </div>
    <aside class="aside-info">
      <?php if (!empty($skyscraper)) : ?>
      <a href="<?php echo esc_url($link_skyscraper); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <?php endif; ?>

      <h2>Notícias recentes</h2>
      <?php
      $recent_posts_query = new WP_Query(array(
          'post_type'      => 'post',
          'posts_per_page' => 5,  // Limita a 5 posts
          'orderby'        => 'date',
          'order'          => 'DESC',  // Do mais recente para o mais antigo
          'post_status'    => 'publish', 
          'no_found_rows'  => true, // Garante que só pegue posts publicados
      ));

if ($recent_posts_query->have_posts()) {
    while ($recent_posts_query->have_posts()) {
        $recent_posts_query->the_post();
        $post_title = CFS()->get('titulo') ?: get_the_title();
        
        if (!empty($post_title)) :
?>
      <div class="item-aside">
        <a href="<?php the_permalink(); ?>">
          <?php 
            $imagem_url = CFS()->get('imagem');
            if (!empty($imagem_url)) {  
            ?>
          <img class="img-post" src="<?php echo esc_url($imagem_url); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <?php } ?>
          <h3><?php echo esc_html($post_title); ?></h3>
        </a>
      </div>
      <?php
        endif;
    }
       wp_reset_postdata();  // Reseta a query para evitar conflitos
      } else {
          echo '<p>Nenhum post encontrado.</p>';
      }
      ?>
      <?php if ($big_stamp != '') { ?>
      <a href="<?php echo esc_url($link_big_stampr); ?>">
        <img src="<?php echo esc_url($big_stamp); ?>">
      </a>
      <?php } ?>

    </aside>
  </div>
</div>
<?php endwhile; else: endif; ?>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
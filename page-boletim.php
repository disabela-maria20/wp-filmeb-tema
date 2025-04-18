<?php
// Template Name: Boletim
get_header();

$edicoes_query = new WP_Query(array(
  'post_type' => 'edicoes',
  'posts_per_page' => 1,
  'orderby' => 'date',     
  'order' => 'DESC',
));

$banner_id = "78919";
    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $full_banner = CFS()->get('full_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
   
    $link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
    $link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
    $link_full_banner = CFS()->get('link_full_banner', $banner_id);
    $link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
    

?>
<a href="<?php echo esc_url($link_banner_superior) ?>" target="_blank" rel="noopener noreferrer">
  <img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </a>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
    </div>
  </div>
</section>

<?php if (have_posts()): while (have_posts()): the_post(); ?>

<div class="container">
  <div class="grid-list-post-boletim gap-124">
    <div>
      <a href="<?php echo esc_url($link_full_banner); ?>">
        <img src="<?php echo esc_url($full_banner); ?>" class="img-banner" style="padding-bottom: 25px;" alt="banner">
      </a>

      <?php
          if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
          } ?>

      <?php if ($edicoes_query->have_posts()): ?>

      <?php while ($edicoes_query->have_posts()): $edicoes_query->the_post(); ?>
      <h2 class="titulo-cinza"><?php the_title(); ?></h2>
      <div class="posts">
        <?php
          $values = CFS()->get('edicao');
          if (!empty($values) && is_array($values)) { 
            foreach ($values as $post_id) { 
              $the_post = get_post($post_id);
              // echo "<pre>";
              // var_dump( $the_post);
              // echo "</pre>";
            ?>
        <div class="post">
          <?php if( esc_url(CFS()->get('imagem')) != '') {  ?>
          <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <?php }?>
          <div>
            <span class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime($the_post->post_date)); ?></span>
            <a href="<?php echo str_replace("https://filmeb.isabelamribeiro.com.br", get_site_url(), $the_post->guid);  ?>"
              class="read-more">
              <h2><?php echo extrair_texto_apos_traco($the_post->post_title); ?></h2>
            </a>
            <p><?php echo  $the_post->post_content;?></p>
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
      <a href="<?php echo esc_url($link_super_banner); ?>">
        <img src="<?php echo esc_url($super_banner); ?>" class="img-banner" alt="banner">
      </a>
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
      <a href="<?php echo esc_url($link_skyscraper)?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <h2>Boletim da semana</h2>
      <?php
                    $rapidinhas_id = get_cat_ID('Nacional');

                    $recent_posts_query = new WP_Query(array(
                        'post_type' => 'post',
                        'posts_per_page' => 5,
                        'orderby' => 'date',
                        'order' => 'DESC',
                        'category__in' => array($rapidinhas_id),
                    ));

                    if ($recent_posts_query->have_posts()) {
                        while ($recent_posts_query->have_posts()) {
                            $recent_posts_query->the_post();?>
      <div class="item-aside">
        <a href="<?php the_permalink();?>">
          <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3>
        </a>
      </div>
      <?php } wp_reset_postdata();
                    } else {
                        echo '<p>Nenhum post encontrado.</p>';
                    }
                    ?>
    </aside>
    <aside class="aside-boletim">
      <a href="<?php echo esc_url($link_skyscraper)?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <h2>Boletins</h2>
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
  </div>
</div>
<?php endwhile;
else: endif; ?>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
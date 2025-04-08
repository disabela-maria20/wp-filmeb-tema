<?php
// Template Name: Boletim
get_header();

$edicoes_query = new WP_Query(array(
  'post_type' => 'edicoes',
  'posts_per_page' => 1,
  'orderby' => 'date',     
  'order' => 'DESC',
));

$recent_posts_query = new WP_Query(array(
  'category_name' => 'Notícias',
  'posts_per_page' => 6,
));

?>
<img src="<?php echo CFS()->get('banner_moldura'); ?>" class="w-full p-35 img-banner bannerMobile " alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <!-- <img src="<?php echo CFS()->get('banner_moldura'); ?>" class="img-banner" alt="banner"> -->
    <img src="<?php echo CFS()->get('mega_banner'); ?>" class="img-banner " alt="banner">
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>

<div class="container">
  <div class="grid-list-post gap-124">
    <div>
      <img src="<?php echo CFS()->get('full_banner'); ?>" class="img-banner" alt="banner">

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
              <h2><?php echo $the_post->post_title; ?></h2>
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
      <img src="<?php echo CFS()->get('super_banner'); ?>" class="img-banner" alt="banner">
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
      <img src="<?php echo CFS()->get('skyscraper'); ?>" class="img-banner" alt="banner">
      <h2>Notícias recentes</h2>
      <?php if ($recent_posts_query->have_posts()) : ?>
      <div class="aside-flex">
        <?php while ($recent_posts_query->have_posts()) : $recent_posts_query->the_post(); ?>
        <div class="">
          <a href="<?php the_permalink(); ?>" class="read-more">
            <h3><?php the_title(); ?></h3>
          </a>
        </div>
        <?php endwhile; ?>
      </div>
      <?php else : ?>
      <p>Nenhum boletim encontrado.</p>
      <?php endif; wp_reset_postdata();?>
    </aside>
  </div>
</div>
<?php endwhile;
else: endif; ?>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
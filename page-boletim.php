<?php
// Template Name: Boletim
get_header();
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
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-boletim-filme-b-horizontal.png"
        class="logo" alt="cine B" />
      <?php
          if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
          } ?>

      <?php
          $boletim_query = new WP_Query(array(
            'category_name' => 'Boletim',
            'posts_per_page' => 3,
          ));

          if ($boletim_query->have_posts()): ?>
      <div class="posts">
        <?php while ($boletim_query->have_posts()): $boletim_query->the_post(); ?>
        <div class="post">
          <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <span><?php echo get_the_category_list(', '); ?></span>
          <a href="<?php the_permalink(); ?>" class="read-more">
            <h2><?php the_title(); ?></h2>
          </a>
          <p><?php echo esc_html(CFS()->get('descricao') ?: get_the_excerpt()); ?></p>

        </div>
        <?php endwhile; ?>
      </div>
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
      <h2>Boletim da semana</h2>
      <?php
          $rapidinhas_id = get_cat_ID('Rapidinhas');

          $recent_posts_query = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
            'category__in' => array($rapidinhas_id),
          ));

          if ($recent_posts_query->have_posts()) {
            while ($recent_posts_query->have_posts()) {
              $recent_posts_query->the_post(); ?>
      <div class="item-aside">
        <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
          alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
        <a href="<?php the_permalink(); ?>">
          <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3>
        </a>
      </div>
      <?php }
            wp_reset_postdata();
          } else {
            echo '<p>Nenhum post encontrado.</p>';
          }
          ?>
    </aside>
  </div>
</div>
<?php endwhile;
else: endif; ?>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
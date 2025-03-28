<?php
// Template Name:  Categoria Boletim
get_header();
?>

<?php
$current_page_slug = basename(get_permalink());
$category_slug = str_replace('boletim/', '', $current_page_slug);
$banner_id = "184";

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);

$query = new WP_Query($args);

$recent_posts_query = new WP_Query(array(
  'post_type' => 'post',
  'posts_per_page' => 5,
  'orderby' => 'date',
  'order' => 'DESC'
));

if ($query->have_posts()) :
  while ($query->have_posts()) : $query->the_post();

    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $full_banner = CFS()->get('full_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
    $super_banner = CFS()->get('super_banner', $banner_id);

    $link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
    $link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
    $link_full_banner = CFS()->get('link_full_banner', $banner_id);
    $link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
    $link_super_banner = CFS()->get('link_super_banner', $banner_id);
    
?>
<a href="<?php echo esc_url($link_banner_superior)?>" target="_blank" rel="noopener noreferrer">
  <img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile " alt="banner">
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

<?php
  endwhile;
  wp_reset_postdata();
endif;
?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <!-- <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner bannerDesktop" alt="banner"> -->
      <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
    </div>
  </div>
</section>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div class="container">
  <div class="grid-list-post gap-124">
    <div>
      <img src="<?php echo esc_url($full_banner); ?>" class="img-banner" alt="banner">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-boletim-filme-b-horizontal.png"
        class="logo" alt="cine B" />

      <?php if (function_exists('yoast_breadcrumb')) {yoast_breadcrumb('<div id="breadcrumbs">', '</div>'); } ?>
      <div class="post">
        <div class="posts">
          <h1><?php the_title(); ?></h1>
          <div><?php the_content(); ?></div>
        </div>
      </div>



      <img src="<?php echo esc_url($super_banner); ?>" class="img-banner" alt="banner">

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
    <?php
    $banner_id = "185";
    $args = array(
      'post_type' => 'banner-post',
      'posts_per_page' => 1,
    );
    $query = new WP_Query($args);
    if ($query->have_posts()) :while ($query->have_posts()) : $query->the_post();
        $skyscraper = CFS()->get('skyscraper', $banner_id);
    ?>

    <aside class="aside-info">
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
    <?php endwhile; wp_reset_postdata(); endif;?>
  </div>
</div>
<?php endwhile; endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
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

?>
<img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile" alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
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
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
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

      <?php
          $boletim_query = new WP_Query(array(
            'category_name'  => $category_slug, 
            'posts_per_page' => 3,
          ));

          if ($boletim_query->have_posts()) : ?>
      <div class="posts">
        <?php while ($boletim_query->have_posts()) : $boletim_query->the_post(); ?>
        <div class="post">
          <?php if( esc_url(CFS()->get('imagem')) != '') {  ?>
          <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <?php }?>
          <span><?php echo get_the_category_list(', '); ?></span>
          <a href="<?php the_permalink(); ?>" class="read-more">
            <h2><?php the_title(); ?></h2>
          </a>
          <p><?php echo esc_html(CFS()->get('descricao') ?: get_the_excerpt()); ?></p>
        </div>
        <?php endwhile; ?>
      </div>
      <?php else : ?>
      <p>Nenhum boletim encontrado.</p>
      <?php endif; wp_reset_postdata();?>

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

    <aside class="aside-boletim">
      <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      <h2>Boletim da semana</h2>
      <div class="aside-item-boletim">
        <ul>
          <?php  if ($recent_posts_query->have_posts()) {while ($recent_posts_query->have_posts()) { $recent_posts_query->the_post();?>
          <li>
            <?php if( esc_url(CFS()->get('imagem')) != '') {  ?>
            <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
              alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
            <?php }?>
            <a href="<?php the_permalink(); ?>">
              <h2><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h2>
            </a>
            <?php  } wp_reset_postdata(); } else {  echo '<p>Nenhum post encontrado.</p>'; } ?>
          </li>
        </ul>
      </div>
    </aside>
    <?php endwhile; wp_reset_postdata(); endif;?>
  </div>
</div>
<?php endwhile;
endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
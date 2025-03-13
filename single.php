<?php get_header(); ?>

<?php
$banner_id = "185";
$author_id = get_the_author_meta('ID');

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);

$query = new WP_Query($args);

if ($query->have_posts()):
  while ($query->have_posts()):
    $query->the_post();

    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $full_banner = CFS()->get('full_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
    $super_banner = CFS()->get('super_banner', $banner_id);
    $modulo = CFS()->get('modulo', $banner_id);

    $link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
    $link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
    $link_full_banner = CFS()->get('link_full_banner', $banner_id);
    $link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
    $link_super_banner = CFS()->get('link_super_banner', $banner_id);
    $link_modulo = CFS()->get('link_modulo', $banner_id);

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

<?php endwhile; wp_reset_postdata(); endif; ?>

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

<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>

<div class="container">
  <div class="grid-list-post gap-124">
    <div>
      <a href="<?php echo esc_url($link_full_banner); ?>">
        <img src="<?php echo esc_url($full_banner); ?>" class="img-banner" alt="banner">
      </a>
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-boletim-filme-b-horizontal.png"
        class="logo" alt="cine B" />

      <?php if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
          } ?>
      <section class="post">
        <div>
          <strong class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></strong>
          <?php if( esc_url(CFS()->get('imagem')) != '') {  ?>
          <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <?php }?>

          <!-- <a href="" class="autor">
            <img src="<?php echo get_avatar_url($author_id) ?>"
              alt="<?php get_the_author_meta('display_name', $author_id) ?>">
          </a> -->
        </div>
        <div class="post-content">
          <h1><?php the_title(); ?></h1>

          <div class="post-text">
            <?php the_content(); ?>
          </div>
        </div>
      </section>
      <a href="<?php echo esc_url($link_modulo); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($modulo); ?>" class="img-banner" alt="banner">
      </a>
    </div>
    <?php get_template_part('components/Aside/index'); ?>
  </div>
</div>

<?php endwhile;
endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<script>
var splide = new Splide('#datas', {
  arrows: true,
  pagination: false,
});
splide.mount();
</script>
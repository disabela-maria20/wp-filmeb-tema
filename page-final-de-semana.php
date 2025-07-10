<?php
// Template Name: Final de Semana
get_header();
$banner_id = "78847";



    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $full_banner = CFS()->get('full_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
    $modulo = CFS()->get('modulo', $banner_id);
     
    $link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
    $link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
    $link_full_banner = CFS()->get('link_full_banner', $banner_id);
    $link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
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

      <?php if ( function_exists('bcn_display') ) {
    bcn_display();
} ?>

      <div class="post">
        <div class="post-content">
          <?php if (has_post_thumbnail()): ?>
          <div class="post-thumbnail">
            <?php the_post_thumbnail('full'); ?>
          </div>
          <?php endif; ?>
          <section class="table-over">
            <div class="tabela-boletim">
              <h1><?php the_title(); ?></h1>
              <?php the_content(); ?>
            </div>
          </section>
        </div>
      </div>
      <a href="<?php echo esc_url($link_modulo); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($modulo); ?>" class="img-banner" alt="banner">
      </a>
    </div>

    <aside class="aside-boletim">
      <?php if( esc_url($skyscraper != '')) {  ?>
      <a href="<?php echo esc_url($link_skyscraper); ?>">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <?php }?>
      <h2>Boletins</h2>
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
  </div>
</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
<?php get_template_part('components/Footer/index'); ?>


<script>
var splide = new Splide('#datas', {
  arrows: true,
  pagination: false,
});
splide.mount();
</script>
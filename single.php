<?php
get_header();
?>

<?php
$current_page_slug = basename(get_permalink());
$category_slug = str_replace('boletim/', '', $current_page_slug);
$banner_id = "185";
$author_id = get_the_author_meta('ID');

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);

$query = new WP_Query($args);

if ($query->have_posts()) :
  while ($query->have_posts()) : $query->the_post();

    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $full_banner = CFS()->get('full_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
    $super_banner = CFS()->get('super_banner', $banner_id);

?>
<img src="<?php echo esc_url($banner_superior); ?>" class="img-banner bannerMobile" alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner" alt="banner">
    <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
  </div>
</div>



<?php
  endwhile;
  wp_reset_postdata();
endif;
?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner bannerDesktop" alt="banner">
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

      <?php if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
          } ?>
      <section class="post">
        <div>
          <strong class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></strong>
          <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <a href="" class="autor">
            <img src="<?php echo get_avatar_url($author_id) ?>"
              alt="<?php get_the_author_meta('display_name', $author_id) ?>">
          </a>
        </div>
        <div class="post-content">
          <h1><?php the_title(); ?></h1>
          <?php if (has_post_thumbnail()): ?>
          <div class="post-thumbnail">
            <?php the_post_thumbnail('large'); ?>
          </div>
          <?php endif; ?>
          <div class="post-text">
            <?php the_content(); ?>
          </div>
        </div>
      </section>
      <img src="<?php echo esc_url($super_banner); ?>" class="img-banner" alt="banner">
      <h3 class="titulo">Rapidinha</h3>
      <section class="home_lista_rapinhas bannerMobile">
        <div class="owl-carousel rapidinhas">
          <?php display_rapidinhas(); ?>
        </div>
      </section>
      <section class="home_lista_rapinhas bannerDesktop">
        <div class="grid gap-32">
          <?php display_rapidinhas(); ?>
        </div>
      </section>
    </div>
    <?php get_template_part('components/Aside/index'); ?>
  </div>
</div>

<?php endwhile;
endif; ?>
<?php
// Função para exibir rapidinhas
function display_rapidinhas()
{
  $rapidinhas_query = new WP_Query(array(
    'post_type' => 'rapidinhas',
    'posts_per_page' => 9,
    'orderby' => 'date',
    'order' => 'DESC',
  ));

  if ($rapidinhas_query->have_posts()) {
    $post_count = 0;

    while ($rapidinhas_query->have_posts()) {
      $rapidinhas_query->the_post();

      if ($post_count % 3 == 0) {
        if ($post_count > 0) {
          echo '</div></div>';
        }
        echo '<div class="item"><div class="grid grid-1-lg gap-32">';
      }
?>
<div class="item-rapidinha">
  <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
    alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
  <div>
    <span class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></span>
    <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3>
    <a href="<?php the_permalink(); ?>">Leia mais</a>
  </div>
</div>
<?php
      $post_count++;
    }
    echo '</div></div>';
  } else {
    echo '<p>Nenhum post encontrado.</p>';
  }
  wp_reset_postdata();
}

function display_aside_rapidinhas()
{
  $aside_query = new WP_Query(array(
    'post_type' => 'rapidinhas',
    'posts_per_page' => 5,
    'orderby' => 'date',
    'order' => 'DESC'
  ));

  if ($aside_query->have_posts()) {
    while ($aside_query->have_posts()) {
      $aside_query->the_post();
    ?>
<div class="item-aside">
  <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
    alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
  <a href="<?php the_permalink(); ?>">
    <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3>
  </a>
</div>
<?php
    }
  } else {
    echo '<p>Nenhum post encontrado.</p>';
  }
  wp_reset_postdata();
}
?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<script>
var splide = new Splide('#datas', {
  arrows: true,
  pagination: false,
});
splide.mount();
</script>
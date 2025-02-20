<?php
get_header();
?>

<?php
$current_page_slug = basename(get_permalink());
$category_slug = str_replace('boletim/', '', $current_page_slug);
$banner_id = "23243";
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

    $link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
    $link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
    $link_full_banner = CFS()->get('link_full_banner', $banner_id);
    $link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
    $link_super_banner = CFS()->get('link_super_banner', $banner_id);

    $boletim_query = new WP_Query(array(
      'category_name' => 'Rapidinhas',
      'posts_per_page' => 10,
    ));
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
      <a href="<?php echo esc_url($link_banner_superior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner" alt="banner">
      </a>
      <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
    </div>
  </div>
</section>

<div class="container">
  <div class="grid-list-post-rapidinhas gap-124">
    <div>
      <a href="<?php echo esc_url($link_full_banner); ?>">
        <img src="<?php echo esc_url($full_banner); ?>" class="img-banner" alt="banner">
      </a>
      <?php if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
      } ?>
      <div class="post-content-semanal">
        <h1><?php the_title(); ?> -
          <?php echo date_i18n('d \d\e F \d\e Y', strtotime(CFS()->get('data'))) ?>
        </h1>
        <div class="posts">
          <?php
          $values = CFS()->get('edicao');
          if (!empty($values) && is_array($values)) {
            foreach ($values as $post_id) {
              $the_post = get_post($post_id);
              ?>
          <div class="item-rapidinha">
            <?php if( esc_url(CFS()->get('imagem')) != '') {  ?>
            <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
              alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
            <?php }?>

            <div>
              <span class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime($the_post->post_date)); ?></span>
              <a href="<?php the_permalink(); ?>" class="read-more">
                <h2><?php echo formatar_codigo($the_post->post_title); ?></h2>
              </a>
              <a href="<?php echo get_template_directory_uri() . '/' . 'rapidinhas/' . $the_post->post_name; ?>">
                Leia mais
              </a>
            </div>
          </div>
          <?php } // Fechamento do foreach ?>
          <?php } // Fechamento do if ?>
        </div>

      </div>
      <div class="pagination">
        <?php
        echo paginate_links(array(
          'total' => $boletim_query->max_num_pages,
          'type' => 'list',
          'prev_text' => __('<'),
          'next_text' => __('>'),
          'mid_size' => 10,
        ));
        ?>
      </div>
    </div>
    <aside class="aside-info">
      <a href="<?php echo esc_url($link_skyscraper); ?>">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>

      <h2>Edições anteriores</h2>
      <?php
      $recent_posts_query = new WP_Query(array(
        'post_type' => 'edicoes',
        'posts_per_page' => 10,
      ));

      if ($recent_posts_query->have_posts()) {
        while ($recent_posts_query->have_posts()) {
          $recent_posts_query->the_post(); ?>
      <div class="item-aside">
        <a href="<?php the_permalink(); ?>" class="edicoes">
          <i class="bi bi-arrow-right-short"></i>
          <?php 
              $texto = the_title();
              echo formatar_data_personalizada($texto);
            ?>
        </a>
      </div>
      <?php } wp_reset_postdata(); }?>
    </aside>
  </div>
</div>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
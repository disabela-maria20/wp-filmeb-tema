<?php
get_header();
?>
<?php

$banner_id = "23243";




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
  <div class="grid-list-post gap-124">
    <div>
      <img src="<?php echo esc_url($full_banner); ?>" class="img-banner" alt="banner">

      <?php if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
      } ?>

      <h1 class="search-title">Resultados para: "<?php echo get_search_query(); ?>"</h1>

      <?php if (have_posts()): ?>
      <div class="posts">
        <?php while (have_posts()): the_post(); ?>
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

      <div class="pagination">
        <?php
          echo paginate_links(array(
            'total' => $wp_query->max_num_pages,
            'type' => 'list',
            'prev_text' => __('<'),
            'next_text' => __('>'),
            'mid_size' => 3,
          ));
          ?>
      </div>

      <?php else: ?>
      <p>Nenhum resultado encontrado para sua pesquisa.</p>
      <?php endif; ?>

    </div>

    <aside class="aside-info">
      <a href="<?php echo esc_url($link_skyscraper); ?>">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <h2>Boletim</h2>
      <?php
      $recent_posts_query = new WP_Query(array(
        'post_type' => 'edicoes',
        'posts_per_page' => 10,
        'orderby'        => 'date',     
        'order'          => 'DESC' 
      ));
      if ($recent_posts_query->have_posts()) {while ($recent_posts_query->have_posts()) { $recent_posts_query->the_post(); ?>
      <div class="item-aside">
        <a href="<?php the_permalink(); ?>" class="link-post-semanal">
          <h3>
            <?php 
              $texto = the_title();
              echo formatar_data_personalizada($texto);
            ?>
          </h3>
        </a>
      </div>
      <?php }  wp_reset_postdata(); } else {echo '<p>Nenhum post encontrado.</p>';}?>
    </aside>
  </div>
</div>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
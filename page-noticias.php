<?php

get_header();

$rapidinhas_id = get_cat_ID('Rapidinhas');

?>
<img src="<?php echo esc_url(CFS()->get('banner_moldura')); ?>" class="w-full p-35 img-banner bannerMobile"
  alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <!-- <img src="<?php echo esc_url(CFS()->get('banner_moldura')); ?>" class="img-banner" alt="banner"> -->
    <img src="<?php echo esc_url(CFS()->get('mega_banner')); ?>" class="img-banner" alt="banner">
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile bg-gray ">
    <div class="grid-banner-superior">
      <!-- <img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner bannerDesktop" alt="banner"> -->
      <img src="<?php echo CFS()->get('mega_banner'); ?>" class="img-banner " alt="banner">
    </div>
  </div>
</section>


<?php if (have_posts()): while (have_posts()):the_post(); ?>
<div class="container">
  <div class="grid-list-post gap-124">
    <div>
      <img src="<?php echo esc_url(CFS()->get('full_banner')); ?>" class="img-banner" alt="banner">
      <img
        src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo-boletim-filme-b-horizontal.png'); ?>"
        class="logo" alt="cine B" />
      <?php if (function_exists('yoast_breadcrumb')) {
          yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
        } ?>

      <?php
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $boletim_query = new WP_Query(array(
          'category_name' => 'Notícias',
          'posts_per_page' => 4,
          'paged' => $paged,
        ));

      if ($boletim_query->have_posts()): ?>
      <div class="posts">
        <?php while ($boletim_query->have_posts()):$boletim_query->the_post(); ?>
        <div class="post">
          <div class="item">
            <?php if( esc_url(CFS()->get('imagem')) != '') {  ?>
            <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
              alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
            <?php }?>
            <div>
              <span><?php echo get_the_category_list(', '); ?></span>
              <a href="<?php the_permalink(); ?>" class="read-more">
                <h2><?php the_title(); ?></h2>
              </a>
              <p><?php echo esc_html(CFS()->get('descricao') ?: get_the_excerpt()); ?></p>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

      <?php else: ?>
      <p>Nenhum boletim encontrado.</p>
      <?php endif; ?>
      <div class="pagination">
        <?php
            echo paginate_links(array(
              'total' => $boletim_query->max_num_pages,
              'type' => 'list',
              'prev_text' => __('<'),
              'next_text' => __('>'),
              'mid_size' => 3,
            ));
            ?>
      </div>
      <?php wp_reset_postdata(); ?>
    </div>

    <aside class="aside-info">
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
  </div>
</div>

<?php endwhile;
else:
endif; ?>
<?php get_template_part('components/Footer/index'); ?>

<?php get_footer(); ?>
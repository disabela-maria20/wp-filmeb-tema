<?php

get_header();
?>
<?php
$banner_id = "23243";

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);


$query = new WP_Query($args);

if ($query->have_posts()): while ($query->have_posts()): $query->the_post();

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
      <?php
      $boletim_query = new WP_Query(array(
        'post_type' => 'edicoes',
        'posts_per_page' => 10,
      ));
      

      if ($boletim_query->have_posts()): ?>
      <h1>Toda as Edições</h1>
      <?php while ($boletim_query->have_posts()): $boletim_query->the_post(); ?>
      <div class="grid-semanal">
        <a href="<?php the_permalink(); ?>" class="link-post-semanal">
          <h2><?php the_title(); ?></h2>
        </a>

        <?php
        $values = CFS()->get('edicao');
        if (!empty($values) && is_array($values)) {
            // Pega apenas os dois primeiros itens do array
            $primeiros_dois = array_slice($values, 0, 3);

            foreach ($primeiros_dois as $post_id) {
                $the_post = get_post($post_id);
                ?>
        <a href="<?php echo str_replace("https://filmeb.isabelamribeiro.com.br", get_site_url(), $the_post->guid); ?>"
          class="link-lista-rapidinha">
          <i class="bi bi-arrow-right-short"></i>
          <span><?php echo $the_post->post_title; ?></span>
        </a>
        <?php } // Fechamento do foreach ?>
        <?php } // Fechamento do if ?>
      </div>
      <?php endwhile; ?>
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
      <?php else: ?>
      <p>Nenhuma rapidinha encontrada.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
    <aside class="aside-boletim">
      <a href="<?php echo esc_url($link_skyscraper)?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <h2>Boletins</h2>
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
  </div>



</div>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
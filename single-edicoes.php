<?php
get_header();
?>

<?php

$banner_id = "78847";

$author_id = get_the_author_meta('ID');
    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $full_banner = CFS()->get('full_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
   

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
    </div>
  </div>
</section>

<div class="container">
  <div class="grid-list-post-rapidinhas gap-124">
    <div>
      <a href="<?php echo esc_url($link_full_banner); ?>">
        <img src="<?php echo esc_url($full_banner); ?>" style="padding-bottom: 25px;" class="img-banner" alt="banner">
      </a>
      <div id="breadcrumbs">
        <?php if ( function_exists('bcn_display') ) {
          bcn_display();
      } ?>
      </div>

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
            <?php $imagem = CFS()->get('imagem') ?: ''; ?>
            <?php if( $imagem !== '' ) { ?>
            <img src="<?php echo esc_url($imagem); ?>"
              alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
            <?php } ?>
            <div>

              <a href="<?php the_permalink(); ?>" class="read-more">
                <h2><?php echo $the_post->post_title; ?></h2>
              </a>
              <span class="data">
                <?php $data=strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>
              </span>
              <p><?php echo $the_post->post_content?></p>
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
      <?php if( esc_url($skyscraper != '')) {  ?>
      <a href="<?php echo esc_url($link_skyscraper); ?>">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <?php }?>

      <h2>Edições anteriores</h2>
      <?php 
          $recent_edicoes_query = new WP_Query(array(
            'post_type' => 'edicoes',
            'posts_per_page' => -1,
          ));

          $posts_ordenados = [];

          if ($recent_edicoes_query->have_posts()) {
            while ($recent_edicoes_query->have_posts()) {
              $recent_edicoes_query->the_post();
              
              $titulo = get_the_title();
              preg_match('/Edição (\d+)/', $titulo, $match);
              $numero = isset($match[1]) ? intval($match[1]) : 0;

              $data_raw = CFS()->get('data');
              $data_formatada = $data_raw ? date_i18n('d M Y', strtotime($data_raw)) : '';

              $posts_ordenados[] = [
                'numero' => $numero,
                'permalink' => get_permalink(),
                'data' => $data_formatada
              ];
            }

            // Ordena da maior para a menor edição
            usort($posts_ordenados, function ($a, $b) {
              return $b['numero'] - $a['numero'];
            });

            foreach (array_slice($posts_ordenados, 0, 10) as $post) {
              ?>
      <div class="item-aside">
        <a href="<?php echo esc_url($post['permalink']); ?>" class="edicoes">
          <i class="bi bi-arrow-right-short"></i>
          <?php echo 'Edição ' . $post['numero'] . ' – ' . strtolower($post['data']); ?>
        </a>
      </div>
      <?php
            }

            wp_reset_postdata();
          }
          ?>

    </aside>
  </div>
</div>


<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
<?php
get_header();
?>

<?php
$banner_id = "78847";


$boletim_query = new WP_Query(array(
  'category_name' => 'Rapidinhas',
  'posts_per_page' => 10,
));
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

?>
<div class="w-full p-35 img-banner bannerMobile">
  <?php echo do_shortcode('[bm_banner id="399779"]');?>
</div>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <?php echo do_shortcode('[bm_banner id="400027"]');?>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <?php echo do_shortcode('[bm_banner id="400027"]');?>
    </div>
  </div>
</section>

<div class="container">
  <div class="grid-list-post-rapidinhas gap-124">
    <div>
      <div style="padding-bottom: 25px;">
        <?php echo do_shortcode('[bm_banner id="399750"]');?>
      </div>
      <div id="breadcrumbs">
        <?php if ( function_exists('bcn_display') ) {
          bcn_display();
      } ?>
      </div>
      <?php
          $boletim_query = new WP_Query(array(
            'post_type' => 'rapidinhas',
            'posts_per_page' => 10,
            'orderby' => 'date',
            'order' => 'DESC',
            'paged' => $paged,
          ));


          if ($boletim_query->have_posts()): ?>
      <h1>Rapidinhas</h1>
      <div class="posts">
        <?php while ($boletim_query->have_posts()): $boletim_query->the_post(); ?>
        <div class="item-rapidinha">
          <?php $imagem = CFS()->get('imagem') ?: ''; ?>
          <?php if( $imagem !== '' ) { ?>
          <img src="<?php echo esc_url($imagem); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <?php } ?>
          <div>
            <a href="<?php the_permalink(); ?>" class="read-more">
              <h2><?php echo get_post()->post_title;?></h2>
            </a>
            <span class="data">
              <?php $data=strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>
            </span>
            <a href="<?php the_permalink(); ?>">
              Leia mais
            </a>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
      <div class="pagination">
        <?php
             echo paginate_links(array(
              'base' => get_pagenum_link(1) . '%_%',
              'format' => 'page/%#%/',
              'current' => max(1, get_query_var('paged')),
              'total' => $boletim_query->max_num_pages,
              'prev_text' => __('<'),
              'next_text' => __('>'),
              'mid_size' => 2,
              'type' => 'list', // pode ser 'plain' se preferir sem <ul>
            ));

            ?>
      </div>
      <?php else: ?>
      <p>Nenhuma rapidinha encontrada.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
    <aside class="aside-info">
      <?php echo do_shortcode('[bm_banner id="399753"]');?>
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

      <a class="btn-edicoes" href="<?php echo '';?>"> Acessar edições anteriores</a>
    </aside>
  </div>



</div>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
<?php
get_header();
?>

<?php

$banner_id = "78847";

$author_id = get_the_author_meta('ID');
   
    $boletim_query = new WP_Query(array(
      'category_name' => 'Rapidinhas',
      'posts_per_page' => 10,
    ));
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
  <div class="grid-list-post-edicoes gap-124 edicoes">
    <div>
      <div style="padding-bottom: 25px;">
        <?php echo do_shortcode('[bm_banner id="399750"]');?>
      </div>
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
               $chapeu = CFS()->get('chapeu');
              ?>
          <div class="item">
            <?php if ($chapeu) echo  '<span>' . $chapeu . '</span>'; ?>

            <?php if (!empty(CFS()->get('imagem', $post_id))) { ?>
            <img class="bg-banner" src="<?php echo esc_url(CFS()->get('imagem', $post_id)); ?>"
              alt="<?php echo esc_attr(CFS()->get('titulo', $post_id) ?: get_the_title()); ?>" />
            <?php } ?>
            <div>

              <a href="<?php the_permalink(); ?>" class="read-more">
                <h2><?php echo $the_post->post_title; ?></h2>
              </a>
              <strong class="data">
                <?php $data=strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>
              </strong>
              <p><?php echo do_shortcode(apply_filters('the_content', $the_post->post_content)); ?></p>
            </div>
          </div>
          <?php } // Fechamento do foreach ?>
          <?php } // Fechamento do if ?>
        </div>

        <div class="posts-rapidinhas">
          <h2 class="title-rapidinha">Rapidinhas</h2>
          <?php
                    $rapidinhas = CFS()->get('rapidinha');
                    if (!empty($rapidinhas) && is_array($rapidinhas)) {
                        foreach ($rapidinhas as $post_id) {
                            $the_post = get_post($post_id);
                            setup_postdata($the_post);
                            $image_url = CFS()->get('imagem', $post_id);
                            $title = CFS()->get('titulo', $post_id) ?: get_the_title($post_id);
                            $post_date = $the_post->post_date;
                            $data = strtotime($post_date);
                            ?>
          <div class="item">
            <a href="<?php echo get_permalink($post_id); ?>" class="item-rapidinha">
              <?php if (!empty($image_url)) { ?>
              <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" />
              <?php } ?>
              <div>
                <span class="data">
                  <h3><?php echo esc_html($title); ?></h3>
                  <span>
                    <?php
                                                if ($data !== false) {
                                                    echo date('j', $data) . ' ' . mb_substr(strtolower(date_i18n('F', $data)), 0, 3) . ' ' . date('Y', $data);
                                                }
                                                ?>
                  </span>
                  <span class="leia-mais">Leia mais</span>
                </span>
              </div>
            </a>
          </div>
          <?php
                        }
                        wp_reset_postdata();
                    }
                    ?>
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

    </aside>
  </div>
</div>


<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
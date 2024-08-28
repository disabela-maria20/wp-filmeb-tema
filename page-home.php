<img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner bannerMobile" alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner" alt="banner">
    <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
  </div>
</div>

<?php
// Template Name: Home
get_header();
?>
<div class="container bannerMobile bg-gray padding-banner ">
  <div class="grid-banner-superior">
    <img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner bannerDesktop" alt="banner">
    <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
  </div>
</div>

<?php if (have_posts()):while (have_posts()):the_post(); ?>

<div class="container">
  <section class="owl-carousel slide">
    <?php 
    $recent_posts_query = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ));

    if ($recent_posts_query->have_posts()) {
        while ($recent_posts_query->have_posts()) {
            $recent_posts_query->the_post();
            ?>
            <div class="item">
                <img src="<?php echo esc_url(CFS()->get('imagem')); ?>" alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
                <div>
                  <span><?php echo get_the_category_list(', '); ?></span>
                  <a href="<?php the_permalink();?>"> <h2><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h2></a>
                  <p><?php echo esc_html(CFS()->get('descricao') ?: get_the_excerpt()); ?></p>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo '<p>Nenhum post encontrado.</p>';
    }
    ?>
  </section>
  <section class="home_table">
    <div class="grid grid-2-lg gap-32">
      <div>
        <div class="titulo">
          <h2>10 maiores bilheterias do ano no Brasil</h2>
          <span></span>
        </div>
        <?php echo do_shortcode('[table id=Brasil /]');?>
        <span>De 08 a 12/05/2024 - Fonte: Filme B Box Office</span>
      </div>
      <div>
        <div class="titulo">
          <h2>10 maiores bilheterias do ano no Brasil</h2>
          <span></span>
        </div>
        <?php echo do_shortcode('[table id=Brasil /]');?>
        <span>De 08 a 12/05/2024 - Fonte: Filme B Box Office</span>
      </div>
    </div>
  </section>
  <section class="home_newllater">
    <div class="container">
      <img src="<?php echo CFS()->get('banner_newsllater'); ?>" class="img-banner " alt="banner">
    </div>
  </section>
  <!-- Area de filmes e newsllater -->
  <section class="home_lista_noticias">
    <h2>Publicações recentes</h2>
      <div class="grid grid-2-lg gap-32">
        <?php 
          $rapidinhas_id = get_cat_ID('Rapidinhas');

          $recent_posts_query = new WP_Query(array(
              'post_type'      => 'post',
              'posts_per_page' => 10,
              'orderby'        => 'date',
              'order'          => 'DESC',
              'category__not_in' => array($rapidinhas_id),
          ));

          if ($recent_posts_query->have_posts()) {
              while ($recent_posts_query->have_posts()) {
                  $recent_posts_query->the_post();
                  ?>
                  <div class="item">
                    <img src="<?php echo esc_url(CFS()->get('imagem')); ?>" alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
                    <div>
                      <span><?php echo get_the_category_list(', '); ?></span>
                      <a href="<?php the_permalink();?>"> <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3></a>
                      <span class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></span>
                    </div>

                  </div>
                  <?php
              }
              wp_reset_postdata();
          } else {
              echo '<p>Nenhum post encontrado.</p>';
          }
        ?>
      </div>
  </section>
  <section class="home_lista_rapinhas bannerMobile">
    <h2>Rapidinhas</h2>
    <div class="owl-carousel rapidinhas">
      <?php 
        $rapidinhas_id = get_cat_ID('Rapidinhas');

        $rapidinhas_posts_query = new WP_Query(array(
            'post_type'      => 'post',
            'posts_per_page' => 9, 
            'orderby'        => 'date',
            'order'          => 'DESC',
            'category__in'   => array($rapidinhas_id),
        ));

        if ($rapidinhas_posts_query->have_posts()) {
            $post_count = 0;

            while ($rapidinhas_posts_query->have_posts()) {
                $rapidinhas_posts_query->the_post();

               
                if ($post_count % 3 == 0) {
                    if ($post_count > 0) {
                        echo '</div>'; // Fecha a div.grid anterior
                        echo '</div>'; // Fecha a div.item anterior
                    }
                    echo '<div class="item"><div class="grid grid-1-lg gap-32">';
                }

                ?>
                  <div class="item-rapidinha">
                    <img src="<?php echo esc_url(CFS()->get('imagem')); ?>" alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
                    <div>
                      <span class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></span>
                      <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3>
                      <a href="<?php the_permalink();?>">Leia mais</a>
                    </div> 
                  </div>
                <?php

                $post_count++;
            }

            // Fecha a última div.item
            echo '</div>'; // Fecha a div.grid
            echo '</div>'; // Fecha a div.item

            wp_reset_postdata();
        } else {
            echo '<p>Nenhum post encontrado.</p>';
        }
      ?>
    </div>
  </section>
  <section class="home_lista_rapinhas bannerDesktop">
    <h2>Rapidinhas</h2>
    <div class="grid grid-2-lg gap-32"> 
    <?php 
      $rapidinhas_id = get_cat_ID('Rapidinhas');

      $recent_posts_query = new WP_Query(array(
              'post_type'      => 'post',
              'posts_per_page' => 10,
              'orderby'        => 'date',
              'order'          => 'DESC',
              'category__in' => array($rapidinhas_id),
      ));

      if ($recent_posts_query->have_posts()) {
        while ($recent_posts_query->have_posts()) { $recent_posts_query->the_post(); ?>
                 <div class="item-rapidinha">
                    <img src="<?php echo esc_url(CFS()->get('imagem')); ?>" alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
                    <div>
                      <span class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></span>
                      <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3>
                      <a href="<?php the_permalink();?>">Leia mais</a>
                    </div> 
                  </div> 
        <?php
          }
            wp_reset_postdata();
          } else {
              echo '<p>Nenhum post encontrado.</p>';
          }
        ?>

    </div>
  </section>



</div>

  
<?php endwhile; else: endif; ?>

<?php get_footer(); ?>
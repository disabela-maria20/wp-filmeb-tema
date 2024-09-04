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
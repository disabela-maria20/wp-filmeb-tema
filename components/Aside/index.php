<?php
$banner_id = "185";
$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);

$query = new WP_Query($args);

if ($query->have_posts()) :
  while ($query->have_posts()) : $query->the_post();
  
    $skyscraper = CFS()->get('skyscraper', $banner_id);
?>

    <aside class="aside-boletim">
      <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      <h2>Boletim da semana</h2>
      <div class="aside-item-boletim">
        <div>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/final-de-semana.png" alt="cine B" />
          <a href="<?php echo get_template_directory_uri(); ?>/fim-de-semana-brasil">
            Fim de Semana Brasil
          </a>
        </div>
        <div>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/final-de-global.png" alt="cine B" />
          <a href="<?php echo get_template_directory_uri(); ?>/fim-de-semana-global">
            Fim de Semana Global
          </a>
        </div>
        <div>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/rapidinhas.png" alt="cine B" />
          <a href="<?php echo get_template_directory_uri(); ?>/rapidinhas">
            Rapidinhas
          </a>
        </div>
        <div>
          <img src="<?php echo get_template_directory_uri(); ?>/assets/images/banner/opiniao.png" alt="cine B" />
          <a href="<?php echo get_template_directory_uri(); ?>/opiniao">
            Fim de Semana Brasil
          </a>
        </div>
      </div>
    </aside>

<?php
  endwhile;
  wp_reset_postdata();
endif;
?>
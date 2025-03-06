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

if ($query->have_posts()) :
  while ($query->have_posts()) : $query->the_post();

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
    <!-- <a href="<?php echo esc_url($link_banner_superior); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner" alt="banner">
    </a> -->
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
      <!-- <a href="<?php echo esc_url($link_banner_superior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_superior); ?>" class="img-banner" alt="banner">
      </a> -->
      <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
    </div>
  </div>
</section>

<div class="container post-content">
  <div class="grid-list-post gap-124">
    <div>
      <div class="views-field views-field-body">
        <div class="field-content">
          <div id="assine-texto">
            <h1>Assine o Filme B</h1>
            <p>A assinatura do Filme B permite acesso ao conteúdo exclusivo do site.</p>
            <div class="assine-texto-wrapper">
              <h4>Boletim</h4>
              <p>Dados e análises sobre o mercado de cinema no Brasil e no mundo, com destaque para o ranking do fim de
                semana no Brasil.</p>
              <p class="small">⋅ Acesso a todas as edições anteriores do Boletim</p>
              <h4>Notícias</h4>
              <p class="assi-noti">Cobertura diária do mercado de cinema: produção, exibição, distribuição, 3D, digital,
                editais, opinião e tendências.<br>&nbsp;</p>
              <h4>Database Brasil</h4>
              <p>Bases de dados anuais com números e comparativos dos setores de exibição, produção e distribuição no
                Brasil desde 2000.</p>
              <p class="small">⋅ Todas as edições dos databases estão disponíveis no site até 2020.</p>
              <h4>Revistas</h4>
              <p>Edições lançadas no Show de Inverno (abril) e Show Búzios (novembro)</p>
              <p class="small">⋅ Versão impressa é enviada aos assinantes (edições suspensas durante a pandemia)</p>
              <div class="assinatura-precos">
                <h3>R$ 700,00 por ano</h3>
                <h4>Formas de pagamento: boleto ou cartão</h4>

                <video controls="" height="180" poster="/poster_assine.png" width="320">
                  <source src="/filmeb_assine.mp4" type="video/mp4">
                </video>
                <div class="area-btn">
                  <a href="<?php echo get_site_url(); ?>/finalizar-compra/?add-to-cart=77471/">Assinar</a>
                </div>
              </div>
            </div>
          </div>
        </div>
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
        'orderby' => 'date',     
        'order' => 'DESC' 
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
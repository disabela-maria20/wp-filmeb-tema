<?php get_header(); ?>

<?php
$banner_id = "78847";
$author_id = get_the_author_meta('ID');

$boletim_query = new WP_Query(array(
  'category_name'  => 'Rapidinhas',
  'posts_per_page' => 10,
));
?>

<!-- Banner Mobile -->
<div class="w-full p-35 img-banner bannerMobile">
  <?php echo do_shortcode('[bm_banner id="399779"]'); ?>
</div>

<!-- Banner Desktop -->
<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <?php echo do_shortcode('[bm_banner id="400027"]'); ?>
  </div>
</div>

<?php 
get_template_part('components/MenuMobile/index'); 
get_template_part('components/MenuDesktop/index'); 
?>

<section class="bg-gray">
  <div class="bannerMobile bg-gray padding-banner">
    <div class="grid-banner-superior">
      <?php echo do_shortcode('[bm_banner id="400027"]'); ?>
    </div>
  </div>
</section>

<div class="container">
  <div class="grid-list-post-rapidinhas gap-124">
    <div>
      <!-- Banner superior -->
      <div style="padding-bottom: 25px;">
        <?php echo do_shortcode('[bm_banner id="399750"]'); ?>
      </div>

      <!-- Breadcrumbs -->
      <div id="breadcrumbs">
        <?php if (function_exists('bcn_display')) { bcn_display(); } ?>
      </div>

      <!-- Conteúdo principal -->
      <div class="post-content">
        <div>
          <?php 
          $edicao = CFS()->get('edicao');
          $imagem_url = CFS()->get('imagem');
          if (!empty($imagem_url)) {  
            if ($edicao) echo '<strong class="data" style="margin-bottom: 15px;display: block;">' . esc_html($edicao) . '</strong>'; 
          ?>
          <img class="img-post" src="<?php echo esc_url($imagem_url); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <?php } ?>
        </div>

        <strong class="data">
          <?php 
          $data = strtotime(CFS()->get('data'));
          echo date('j', $data) . ' ' . mb_substr(strtolower(date_i18n('F', $data)), 0, 3) . ' ' . date('Y', $data);
          ?>
        </strong>

        <h1 class="opem"><?php echo get_post()->post_title; ?></h1>

        <!-- Autor -->
        <div class="autor">
          <img
            src="<?php echo esc_url(!empty(CFS()->get('imagem_autor')) ? CFS()->get('imagem_autor') : get_template_directory_uri() . '/assets/images/logo.png'); ?>"
            alt="<?php echo esc_attr(!empty(CFS()->get('nome_autor')) ? CFS()->get('nome_autor') : 'Filme B'); ?>">
          <strong>
            <?php echo empty(CFS()->get('nome_autor')) ? 'Filme B' : esc_html(CFS()->get('nome_autor')); ?>
          </strong>
        </div>

        <!-- Texto -->
        <div class="post-text">
          <?php $id_rapidinha = get_the_ID(); ?>
          <?php the_content(); ?>
        </div>

        <!-- Banner inferior -->
        <div style="padding-bottom: 25px;">
          <?php echo do_shortcode('[bm_banner id="399762"]'); ?>
        </div>

        <!-- Paginação -->
        <div class="pagination">
          <?php 
          echo paginate_links(array(
            'total'     => $boletim_query->max_num_pages,
            'type'      => 'list',
            'prev_text' => __('<'),
            'next_text' => __('>'),
            'mid_size'  => 10,
          )); 
          ?>
        </div>
      </div>
    </div>

    <!-- Aside -->
    <aside class="aside-info">
      <?php echo do_shortcode('[bm_banner id="399753"]'); ?>
      <h2>Últimas Rapidinhas</h2>

      <?php
      $rapidinhas_posts_query = new WP_Query(array(
        'post_type'      => 'rapidinhas',
        'posts_per_page' => 8,
      ));
      ?>

      <?php if ($rapidinhas_posts_query->have_posts()) : ?>
      <?php while ($rapidinhas_posts_query->have_posts()) : $rapidinhas_posts_query->the_post(); ?>
      <a href="<?php the_permalink(); ?>" class="item-rapidinha">
        <?php if (!empty(CFS()->get('imagem'))) : ?>
        <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
          alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
        <?php endif; ?>
        <div>
          <h3><?php the_title(); ?></h3>
          <span class="data">
            <?php
                $data = strtotime(CFS()->get('data'));
                echo date('j', $data) . ' ' . mb_substr(strtolower(date_i18n('F', $data)), 0, 3) . ' ' . date('Y', $data);
                ?>
          </span>
          <span class="leia-mais">Leia mais</span>
        </div>
      </a>
      <?php endwhile; wp_reset_postdata(); ?>
      <?php else : ?>
      <p>Nenhuma rapidinha encontrada.</p>
      <?php endif; ?>
    </aside>
  </div>
</div>

<?php 
get_template_part('components/Footer/index'); 
get_footer(); 
?>
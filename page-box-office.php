<?php 
// Template Name: Box Office 
get_header();
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

<?php if (have_posts()): while (have_posts()): the_post(); ?>

<main>
  <div class="container page-box-office">
    <img class="banner-box" src="<?php echo get_template_directory_uri(); ?>/assets/images/banner-box-office.png"
      alt="">
    <div class="grid-box">
      <div class="box-office">
        <h1> O Box Office do brasil</h1>
        <p>Com a maior credibilidade do mercado, o Box Office Brasil dispõe de dados sobre a exibição no Brasil,
          atualizado diariamente, com informação de público e renda de filmes, cinemas, salas, exibidores,
          distribuidores, cidades, estados e regiões.</p>
        <p>É possível o cruzamento e a filtragem de diversas variáveis, como tecnologia, linguagem, origem e gêneros,
          além da construção de comparativos.</p>
        <p><strong>Uma importante ferramenta para a tomada de decisão do seu negócio.</strong></p>
        <a class="btn" href="<?php echo get_site_url(); ?>/box-office-assine">Assine</a>
      </div>
      <div class="box-office">
        <video src=""></video>
      </div>
    </div>
    <a href="<?php echo CFS()->get('link_super_banner'); ?>">
      <img src="<?php echo CFS()->get('super_banner'); ?>" class="img-banner " alt="banner">
    </a>
    <h2 class="titulo">Outros produtos Filme B | Box Office Brasil</h2>
    <div class="grid-box-produtos">
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/report.png" alt="">
        <h3>FilmeB Report</h3>
        <p>Coleta diária dos dados de público e renda junto aos exibidores, com envios diários por email ou FTP de
          relatórios padronizados para a importação automática no sistema do distribuidor.</p>
      </div>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/progressao-grafica.png" alt="">
        <h3>FilmeB Ontime</h3>
        <p>Ranking top 10 estimado de filmes no fim de semana, com uma projeção nos valores de público, renda, pmi,
          média de público por cinema, média de público por sessão, variação (%) e acumulados.</p>
      </div>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/ancine.webp" alt="">
        <h3>Sadis ANCINE</h3>
        <p>Exportação e envio mensal do relatório SADIS em XML para upload no Sistema Ancine Digital, contendo os dados
          de público e renda dos filmes exibidos pelo distribuidor.
        </p>
      </div>
    </div>
    <a href="<?php echo get_site_url(); ?>/box-office-assine">
      <img class="banner-box"
        src="<?php echo get_template_directory_uri(); ?>/assets/images/banner-box-office-1140x150.png" alt="banner">
    </a>
    <div class="grid-box-assinante">
      <div class="box-office">
        <h2> Se você já é assinante do banco de dados Box Office Brasil, entre aqui.</h2>
        <p>Acesso exclusivo para assinantes do Box Office Brasil. Para saber mais, entre em contato conosco.</p>

        <a href="<?php echo get_site_url(); ?>/box-office-assine" class="btn">Assine</a>
      </div>
      <div class="box-office">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/revenue-operations-collage.jpg"
          alt="banner">
      </div>
    </div>
    <a href="<?php echo CFS()->get('link_modulo'); ?>">
      <img src="<?php echo CFS()->get('modulo'); ?>" class="img-banner " alt="banner">
    </a>
  </div>

</main>

<?php endwhile; else:endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
<?php 
// Template Name: Box Office - Assine
get_header(); ?>

<a href="<?php echo CFS()->get('link_banner_moldura');?>">
  <img src="<?php echo CFS()->get('banner_moldura');?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo CFS()->get('link_mega_banner'); ?>">
      <img src="<?php echo CFS()->get('mega_banner'); ?>" class="img-banner " alt="banner">
    </a>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="container bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <a href="<?php echo CFS()->get('link_mega_banner'); ?>">
        <img src="<?php echo CFS()->get('mega_banner'); ?>" class="img-banner " alt="banner">
      </a>
    </div>
  </div>
</section>

<?php if (have_posts()): while (have_posts()): the_post(); ?>

<main>
  <div class="container page-box-office">
    <img class="banner-box" src="<?php echo get_template_directory_uri(); ?>/assets/images/banner-box-office.png"
      alt="">

    <a href="<?php echo CFS()->get('link_super_banner'); ?>">
      <img src="<?php echo CFS()->get('super_banner'); ?>" class="img-banner " alt="banner">
    </a>
    <section class="area-form">
      <h1>Assine o Box Office Brasil</h1>
      <form action="">
        <label for="nome">
          <input type="text" name="nome" id="nome" placeholder="Nome">
        </label>
        <label for="sobreNome">
          <input type="text" name="sobreNome" id="sobreNome" placeholder="Sobre Nome">
        </label>
        <label for="email">
          <input type="email" name="email" id="email" placeholder="E-mail">
        </label>
        <label for="telefone">
          <input type="email" name="telefone" id="telefone" placeholder="Telefone">
        </label>

        <fieldset>
          <legend>Selecione os produtos do seu interesse</legend>
          <div class="flex">
            <div class="radio">
              <input type="radio" name="Dados" id="Dados" value="email">
              <label for="">Banco de Dados</label>
            </div>
            <div class="radio">
              <input type="radio" name="Report" id="Report" value="ftp">
              <label for="">Filme B Report</label>
            </div>
            <div class="radio">
              <input type="radio" name="Ontime" id="Ontime" value="ftp">
              <label for="">Filme B Ontime</label>
            </div>
            <div class="radio">
              <input type="radio" name="Sadis" id="Sadis" value="ftp">
              <label for="">Sadis ANCINE</label>
            </div>
          </div>
          <div class="msg">
            <label for="mensagem">
              <textarea name="mensagem" id="mensagem" placeholder="Mensagem"></textarea>
            </label>
          </div>
        </fieldset>
        <div class="area-btn-form">
          <button>Enviar</button>
        </div>
      </form>
    </section>
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
    <a href="<?php echo CFS()->get('link_modulo'); ?>">
      <img src="<?php echo CFS()->get('modulo'); ?>" class="img-banner " alt="banner">
    </a>
  </div>

</main>

<?php endwhile; else:endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
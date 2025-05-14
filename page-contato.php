<?php
// Template Name: Contato
get_header();
?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
<main class="container contatos">
  <?php
      if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
      } ?>
  <h1 class="titulo"><?php the_title(); ?></h1>
  <address>
    <h3>Filme B</h3>
    <p>Rua Alcindo Guanabara, 24 / 503</p>
    <p>Centro - Cinelândia, Cep 20031-915.</p>
    <a href="mailto:filmeb@filmeb.com.br">filmeb@filmeb.com.br</a>
  </address>
  <h3 class="titulo">Diretoria</h3>
  <div class="grid grid-3-lg gap-32">
    <div class="card-user">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/paulo.png"
        alt="Paulo Sérgio Almeida" />
      <div>
        <h4>Paulo Sérgio Almeida</h4>
        <a href="mailto:paulosergio@filmeb.com.br">paulosergio@filmeb.com.br</a>
      </div>
    </div>
    <div class="card-user">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/rodigo.png"
        alt="Rodrigo Saturnino Braga" />
      <div>
        <h4>Rodrigo Saturnino Braga</h4>
        <a href="mailto:rodrigo_saturnino@filmeb.com.br">rodrigo_saturnino@filmeb.com.br</a>
      </div>
    </div>
    <div class="card-user">
      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/cristina.png"
        alt="Cristina Siaines" />
      <div>
        <h4>Cristina Siaines</h4>
        <a href="mailto:cristina@filmeb.com.br">cristina@filmeb.com.br</a>
      </div>
    </div>
  </div>
  <div class="grid grid-3-lg gap-32" style="margin: 35px 0;">
    <div class="card-user">
      <h3 class="titulo">Administrativo e Financeiro</h3>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/Lysia.png" alt="Lysia" />
        <div>
          <h4>Lysia Barros</h4>
          <a href="mailto:financeiro@filmeb.com.br">financeiro@filmeb.com.br</a>
        </div>
      </div>


    </div>
    <div class="card-user">
      <h3 class="titulo">Assinaturas</h3>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/Elizabeth.png"
          alt="Elizabeth Ribeiro" />
        <div>
          <h4>Elizabeth Ribeiro</h4>
          <a href="mailto:bethribeiro@filmeb.com.br">bethribeiro@filmeb.com.br</a>
        </div>
      </div>

    </div>
    <div class="card-user">
      <h3 class="titulo">Comunicação, Publicidade e Marketing</h3>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/Cristiane.png"
          alt="Cristiane Denik" />
        <div>
          <h4>Cristiane Denik</h4>
          <a href="mailto:crisdenik@filmeb.com.br">crisdenik@filmeb.com.br</a>
        </div>
      </div>
    </div>

  </div>
  <div class="grid grid-3-lg gap-31">

    <div class="card-user">
      <h3 class="titulo">Redação</h3>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/Fabiano.png"
          alt="Fabiano Ristow" />
        <div>
          <span>Editor</span>
          <h4>Fabiano Ristow</h4>
          <a href="mailto:fabiano@filmeb.com.br">fabiano@filmeb.com.br</a>
        </div>
      </div>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/Taiani.jpg" alt=" Taiani Mendes" />
        <div>
          <span>Repórter e coordenadora do Calendário de Estreias</span>
          <h4> Taiani Mendes</h4>
          <a href="mailto:taiani@filmeb.com.br">taiani@filmeb.com.br</a>
        </div>
      </div>
    </div>
    <div class="card-user">
      <h3 class="titulo">Box Office Brasil</h3>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/Bruno.png" alt="Bruno Salerno" />
        <div>
          <span>Coordenação</span>
          <h4>Bruno Salerno</h4>
          <a href="mailto:brunosalerno@filmeb.com.br">brunosalerno@filmeb.com.br</a>
        </div>
      </div>
      <div class="item">
        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/contatos/Alan.png" alt="Alan Paes" />
        <div>
          <span>Assistente</span>
          <h4>Alan Paes</h4>
          <a href="mailto:alan@filmeb.com.br">alan@filmeb.com.br</a>
        </div>
      </div>
    </div>
  </div>
</main>
<?php endwhile;
else: endif; ?>

<?php get_template_part( 'components/Footer/index'); ?>
<?php get_footer(); ?>
<?php
// Template Name: Anuncie
get_header();
?>

<div class="w-full p-35 img-banner bannerMobile">
  <?php echo do_shortcode('[bm_banner id="399779"]');?>
</div>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <?php echo do_shortcode('[bm_banner id="399761"]');?>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <?php echo do_shortcode('[bm_banner id="399761"]');?>
    </div>
  </div>
</section>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
<main class="container anuncie">
  <h1 class="titulo"><?php the_title(); ?></h1>

  <h2>Formatos de anúncios padrão</h2>
  <section class="area-table">
    <?php echo do_shortcode('[table id=11 /]'); ?>
  </section>
  <?php viewInfo() ?>
  <h2>Formatos de anúncios especiais</h2>
  <section class="area-table">
    <?php echo do_shortcode('[table id=10 /]'); ?>
  </section>
  <?php viewInfo() ?>

  <h2>Formatos exclusivos do Boletim semanal Filme B</h2>
  <section class="area-table">
    <?php echo do_shortcode('[table id=9 /]'); ?>
  </section>
  <?php viewInfo() ?>
  <p></p>
</main>
<?php endwhile;
else: endif; ?>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<?php
function viewInfo()
{
  echo '
        <article class="area-info">
          <div class="icon">
          <i class="bi bi-exclamation-triangle-fill"></i>
            <div>
              <p><strong>Prazo de entrega:</strong>GIF ou JPEG 24h e HTML5 48h;</p>
              <p><strong>Período de exposição:</strong>30 dias corridos;</p>
              <p><strong>Áudio: </strong> não é permitido;</p>
            </div>
          </div>
          <div>
            <p><strong>Envio do arquivo: </strong>No material em GIF ou JPEG, deverá ser informada a URL de direcionamento da peça para sites ou redes sociais.
              Se a opção for em HTML5, será enviado um tutorial sobre a execução do arquivo.</p>
          </div>
        </article>';
}
?>
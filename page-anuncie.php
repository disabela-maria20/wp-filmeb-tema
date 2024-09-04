<?php
// Template Name: Anuncie
get_header();
?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
    <main class="container anuncie">
      <?php
      if (function_exists('yoast_breadcrumb')) {
        yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
      } ?>
      <h1 class="titulo"><?php the_title(); ?></h1>
      
      <h2>Formatos de anúncios padrão</h2>
      <section class="area-table">
        <?php echo do_shortcode('[table id=Formatosdeannciospadro /]'); ?>
      </section>
      <?php viewInfo() ?>
      <h2>Formatos de anúncios especiais</h2>
      <section class="area-table">  
        <?php echo do_shortcode('[table id=Formatosdeannciosespeciais /]'); ?>
      </section> 
      <?php viewInfo() ?>
      
      <h2>Formatos exclusivos do Boletim semanal Filme B</h2>
      <section class="area-table">
        <?php echo do_shortcode('[table id=NomedoanncioDimensespixelsPesoimagensPesoHTML5FormatosAplicaesDuplo600x360500KB600KBGIFJPEGeHTML5IntegradoaocontedodepginasexclusivasdoBoletimMdulo600x160500KB600KBGIFJPEGeHTML5IntegradoaocontedodepginasexclusivasdoBoletimPgina600x1000500KB600KBGIFJPEGeHTML5IntegradoaocontedodepginasexclusivasdoBoletimRetnguloGrande600x450500KB600KBGIFJPEGeHTML5IntegradoaocontedodepginasexclusivasdoBoletim /]'); ?>
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
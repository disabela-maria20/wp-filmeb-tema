<?php get_header(); ?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>
<main class="container padrao">
  <?php if ( function_exists('bcn_display') ) {
    bcn_display();
} ?>
  <h1 class="titulo"><?php the_title(); ?></h1>
  <?php the_content(); ?>
</main>
<?php endwhile; else: ?>

<section class="introducao-interna introducao-geral">
  <div class="container">
    <h1>Não há conteúdo disponível</h1>
  </div>
</section>

<?php endif; ?>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
<?php get_header(); ?>

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

<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>
<main class="container padrao">

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
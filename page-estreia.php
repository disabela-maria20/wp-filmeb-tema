<?php
// Template Name: Estreias
get_header();
?>
<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>

<main>
  <h1>Estreias</h1>
</main>


<?php endwhile; else: endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
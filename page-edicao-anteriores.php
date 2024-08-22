<?php
// Template Name: Edições anteriores
get_header();
?>
<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>

    <h1>servicos</h1>

  <?php endwhile; else: endif; ?>

<?php get_footer(); ?>
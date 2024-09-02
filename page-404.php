<?php
// Template Name: Anuncie
get_header();
?>
<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>

    <h1>404</h1>

  <?php endwhile; else: endif; ?>

<?php get_footer(); ?>
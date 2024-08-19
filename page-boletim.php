<?php
// Template Name: Boletim  
get_header();
?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>
  <h1>Home</h1>
<?php endwhile; else: endif; ?>

<?php get_footer(); ?>
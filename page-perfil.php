<?php
// Template Name: Perfil

get_header();?>
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
  <h1 class="titulo center">Perfil</h1>
  <?php echo do_shortcode('[swpm_profile_form]'); ?>
</main>
<?php endwhile; endif;?>


<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
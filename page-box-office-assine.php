<?php 
// Template Name: Box Office - Assine
get_header(); ?>

<a href="<?php echo CFS()->get('link_banner_superior');?>">
  <img src="<?php echo CFS()->get('banner_superior');?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo CFS()->get('link_banner_inferior'); ?>">
      <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
    </a>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="container bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <a href="<?php echo CFS()->get('link_banner_inferior'); ?>">
        <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
      </a>
    </div>
  </div>
</section>

<?php if (have_posts()): while (have_posts()): the_post(); ?>

<main>
  <h2>Box Office - Assine</h2>
</main>

<?php endwhile; else:endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
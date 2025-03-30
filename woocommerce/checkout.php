<?php
get_header(); ?>

<img src="<?php echo esc_url(CFS()->get('banner_moldura')); ?>" class="w-full p-35 img-banner bannerMobile"
  alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <!-- <img src="<?php echo esc_url(CFS()->get('banner_moldura')); ?>" class="img-banner" alt="banner"> -->
    <img src="<?php echo esc_url(CFS()->get('mega_banner')); ?>" class="img-banner" alt="banner">
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
<div class="container">
  ssds
</div>

<?php endwhile;
else: endif; ?>
<?php get_template_part('components/Footer/index'); ?>

<?php get_footer(); ?>
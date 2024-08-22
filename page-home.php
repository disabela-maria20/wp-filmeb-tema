<img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner bannerMobile" alt="banner">


<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner" alt="banner">
    <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
  </div>
</div>


<?php
// Template Name: Home
get_header();
?>
<div class="container bannerMobile">
  <div class="grid-banner-superior">
    <img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner bannerDesktop" alt="banner">
    <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
  </div>
</div>


<?php if (have_posts()):while (have_posts()):the_post(); ?>

 <section class="owl-carousel slide">
    <?php 
      $slides = CFS()->get('slide'); 
      echo $slide;
      if (!empty($slides)) {
          foreach ($slides as $slide) { ?>
              <div class="item">
                  <img src="<?php echo esc_url($slide['imagem']); ?>" alt="<?php echo esc_attr($slide['title']); ?>" />
                  <div>
                    <span><?php echo esc_html($slide['tag']); ?></span>
                    <h2><?php echo esc_html($slide['titulo']); ?></h2>
                    <p><?php echo esc_html($slide['descricao']); ?></p>
                  </div>
                  
              </div>
        <?php }} ?>
</section>

  
<?php endwhile; else: endif; ?>

<?php get_footer(); ?>
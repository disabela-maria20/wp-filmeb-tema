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
<div class="container bannerMobile bg-gray p-5">
  <div class="grid-banner-superior">
    <img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner bannerDesktop" alt="banner">
    <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
  </div>
</div>

<?php if (have_posts()):while (have_posts()):the_post(); ?>

<div class="container">
  <section class="owl-carousel slide">
    <?php 
    $recent_posts_query = new WP_Query(array(
        'post_type'      => 'post',
        'posts_per_page' => 5,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ));

    if ($recent_posts_query->have_posts()) {
        while ($recent_posts_query->have_posts()) {
            $recent_posts_query->the_post();
            ?>
            <div class="item">
                <img src="<?php echo esc_url(CFS()->get('imagem')); ?>" alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
                <div>
                  <span><?php echo get_the_category_list(', '); ?></span>
                  <a href="<?php the_permalink();?>"> <h2><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h2></a>
                  <p><?php echo esc_html(CFS()->get('descricao') ?: get_the_excerpt()); ?></p>
                </div>
            </div>
            <?php
        }
        wp_reset_postdata();
    } else {
        echo '<p>Nenhum post encontrado.</p>';
    }
    ?>
  </section>
  <section class="home_table">
    <div class="grid grid-2-lg gap-32">
      <div>
        <div class="titulo">
          <h2>10 maiores bilheterias do ano no Brasil</h2>
          <span></span>
        </div>
        <?php echo do_shortcode('[table id=Brasil /]');?>
        <span>De 08 a 12/05/2024 - Fonte: Filme B Box Office</span>
      </div>
      <div>
        <div class="titulo">
          <h2>10 maiores bilheterias do ano no Brasil</h2>
          <span></span>
        </div>
        <?php echo do_shortcode('[table id=Brasil /]');?>
        <span>De 08 a 12/05/2024 - Fonte: Filme B Box Office</span>
      </div>
    </div>
  </section>
  <section class="home_newllater">
    <div class="container">
      <img src="<?php echo CFS()->get('banner_newsllater'); ?>" class="img-banner " alt="banner">
    </div>
  </section>

</div>

  
<?php endwhile; else: endif; ?>

<?php get_footer(); ?>
<?php 
// Template Name: Estreia Teste
get_header();
?>


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
<?php 

  $args = array(
    'post_type'      => 'filmes', // tipo do post
    'posts_per_page' => -1, // ou defina um número fixo
    'meta_query'     => array(
        array(
            'key'     => 'sem_data',
            'value'   => '1',
            'compare' => '='
        )
    )
);

$query = new WP_Query($args);
echo var_dump($query);

?>
<?php if (have_posts()): while (have_posts()): the_post(); ?>

<main>
  teste
</main>

<?php endwhile; else:endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
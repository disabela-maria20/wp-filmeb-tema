<?php
// Template Name: Final de Semana
get_header();

?>
<div class="w-full p-35 img-banner bannerMobile">
  <?php echo do_shortcode('[bm_banner id="399779"]');?>
</div>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <?php echo do_shortcode('[bm_banner id="400027"]');?>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray">
  <div class="bannerMobile bg-gray padding-banner ">
    <div class="grid-banner-superior">
      <?php echo do_shortcode('[bm_banner id="400027"]');?>
    </div>
  </div>
</section>

<?php if (have_posts()):
  while (have_posts()):
    the_post(); ?>

<div class="container">
  <div class="grid-list-post gap-124">
    <div>
      <div style="padding-bottom: 25px;">
        <?php echo do_shortcode('[bm_banner id="399750"]');?>
      </div>

      <div id="breadcrumbs">
        <?php if ( function_exists('bcn_display') ) {
          bcn_display();
      } ?>
      </div>

      <div class="post">
        <div class="post-content">
          <?php if (has_post_thumbnail()): ?>
          <div class="post-thumbnail">
            <?php the_post_thumbnail('full'); ?>
          </div>
          <?php endif; ?>
          <section class="table-over">
            <div class="tabela-boletim">
              <h1><?php the_title(); ?></h1>
              <?php the_content(); ?>
            </div>
          </section>
        </div>
      </div>
      <div style="padding: 25px 0;">
        <?php echo do_shortcode('[bm_banner id="399762"]');?>
      </div>
    </div>

    <aside class="aside-boletim">
      <?php echo do_shortcode('[bm_banner id="399753"]');?>
      <h2>Boletins</h2>
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
  </div>
</div>
<?php endwhile; endif; ?>
<?php get_footer(); ?>
<?php get_template_part('components/Footer/index'); ?>


<script>
var splide = new Splide('#datas', {
  arrows: true,
  pagination: false,
});
splide.mount();
</script>
<?php get_header(); ?>

<?php
$banner_id = "78847";

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);
$author_id = get_the_author_meta('ID');
$query = new WP_Query($args);

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
      <section class="post">
        <div>

          <?php $chapel = CFS()->get('chapel'); // $data=strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>
          <?php $imagem = CFS()->get('imagem') ?: ''; ?>
          <?php 
           
            $imagem_url = CFS()->get('imagem');
            if (!empty($imagem_url)) {  
            ?>
          <?php if ($chapel) echo  '<span>' . $chapel . '</span>'; ?>
          <img class="img-post" src="<?php echo esc_url($imagem_url); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <?php } ?>

        </div>
        <div class="post-content">
          <strong class="data">
            <?php $data=strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>
          </strong>
          <h1><?php the_title(); ?></h1>
          <div class="autor">
            <img src="<?php echo get_avatar_url($author_id) ?>"
              alt="<?php get_the_author_meta('display_name', $author_id) ?>">
            <strong><?php
                      echo (strtolower(get_the_author_meta('display_name', get_post_field('post_author', get_the_ID()))) === 'cineb') 
                          ? 'Filme B' 
                          : get_the_author_meta('display_name', get_post_field('post_author', get_the_ID()));
                      ?></strong>
          </div>
          <div class="post-text">
            <?php the_content(); ?>
          </div>
        </div>
      </section>
      <div style="padding: 25px 0;">
        <?php echo do_shortcode('[bm_banner id="399762"]');?>
      </div>
    </div>
    <aside class="aside-boletim">
      <?php echo do_shortcode('[bm_banner id="399753"]');?>
      <h2>Boletins</h2>
      <?php get_template_part('components/Aside/index'); ?>
      <?php echo do_shortcode('[bm_banner id="399749"]');?>

    </aside>
  </div>
</div>

<?php endwhile;
endif; ?>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<script>
var splide = new Splide('#datas', {
  arrows: true,
  pagination: false,
});
splide.mount();
</script>
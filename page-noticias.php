<?php
// Template Name: Notícias
get_header();

$banner_id = "78847";

$banner_superior = CFS()->get('banner_moldura', $banner_id);
$banner_inferior = CFS()->get('mega_banner', $banner_id);
$full_banner = CFS()->get('full_banner', $banner_id);
$skyscraper = CFS()->get('skyscraper', $banner_id);

$link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
$link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
$link_full_banner = CFS()->get('link_full_banner', $banner_id);
$link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
?>
<a href="<?php echo esc_url($link_banner_superior) ?>" target="_blank" rel="noopener noreferrer">
  <img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile" alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </a>
  </div>
</div>


<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
    </div>
  </div>
</section>
<div class="container">
  <div class="grid-list-post gap-124">
    <div>
      <a href="<?php echo esc_url($link_full_banner); ?>">
        <img src="<?php echo esc_url($full_banner); ?>" class="img-banner" style="padding-bottom: 25px;" alt="banner">
      </a>

      <?php if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
          } ?>

      <?php
          $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
          $boletim_query = new WP_Query(array(
            'posts_per_page' => 8,
            'paged' => $paged,
            'meta_key' => 'data',          
            'meta_type' => 'DATE',         
            'orderby' => 'meta_value',     
            'order' => 'DESC',  
        ));

          if ($boletim_query->have_posts()): ?>
      <div class="posts">
        <?php while ($boletim_query->have_posts()): $boletim_query->the_post(); ?>
        <div class="post">
          <div class="item">
            <?php if (esc_url(CFS()->get('imagem')) != '') {  ?>
            <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
              alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
            <?php } ?>
            <div>
              <a href="<?php the_permalink(); ?>" class="read-more">
                <strong class="data">
                  <?php echo date_i18n('j \d\e F \d\e Y', strtotime((CFS()->get('data')))); ?>
                </strong>
                <h2><?php the_title(); ?></h2>
                <p><?php echo wp_trim_words(esc_html(CFS()->get('descricao') ?: get_the_excerpt()), 20, '...'); ?></p>
              </a>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>

      <?php else: ?>
      <p>Nenhum boletim encontrado.</p>
      <?php endif; ?>
      <div class="pagination">
        <?php
            echo paginate_links(array(
              'total' => $boletim_query->max_num_pages,
              'type' => 'list',
              'prev_text' => __('<'),
              'next_text' => __('>'),
              'mid_size' => 3,
            ));
          ?>
      </div>
      <?php wp_reset_postdata(); ?>
    </div>

    <aside class="aside-boletim">
      <?php if( esc_url($skyscraper != '')) {  ?>
      <a href="<?php echo esc_url($link_skyscraper); ?>">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <?php }?>
      <h2>Boletins</h2>
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
  </div>

</div>


<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>

<script>
var splide = new Splide('#datas', {
  arrows: true,
  pagination: false,
});
splide.mount();
</script>
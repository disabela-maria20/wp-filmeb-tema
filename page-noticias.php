<?php
// Template Name: Notícias
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

      <?php
          $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
          $boletim_query = new WP_Query(array(
              'posts_per_page' => 8,
              'paged' => $paged,
              'meta_key' => 'data',          
              'meta_type' => 'DATE',         
              'orderby' => 'meta_value',     
              'order' => 'DESC',
              'tax_query' => array(
                  array(
                      'taxonomy' => 'category',
                      'field' => 'slug',
                      'terms' => 'boletim',
                      'operator' => 'NOT IN'
                  )
              )
          ));

          if ($boletim_query->have_posts()): ?>
      <div class="posts">
        <?php while ($boletim_query->have_posts()): $boletim_query->the_post(); ?>
        <?php $chapel = CFS()->get('chapel'); ?>
        <div class="post">
          <div class="item">
            <?php $imagem = CFS()->get('imagem') ?: ''; ?>
            <?php if( $imagem !== '' ) { ?>
            <img src="<?php echo esc_url($imagem); ?>" class="img-post-archive"
              alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
            <?php } ?>
            <div>
              <a href="<?php the_permalink(); ?>" class="read-more">
                <?php if ($chapel) echo  '<span>' . $chapel . '</span>'; ?>
                <h2><?php the_title(); ?></h2>
                <span class="data">
                  <?php $data=strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>
                </span> <i>&nbsp;⎸</i>
                <p class="paragrafo">
                  <?php
                    if (has_excerpt()) {
                       echo wp_trim_words(get_the_excerpt(), 20, '...');
                    } else {
                         echo wp_trim_words(esc_html(CFS()->get('descricao') ?: get_the_excerpt()), 20, '...');  
                    }
                    ?>
                </p>
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
      <?php echo do_shortcode('[bm_banner id="399753"]');?>
      <h2>Boletim da semana</h2>
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
<?php
// Template Name: Opiniao
get_header();

$boletim_query = new WP_Query(array(
  'category_name' => 'Rapidinhas',
  'posts_per_page' => 10,
));

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


<?php if (have_posts()): while (have_posts()):the_post(); ?>
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
          'category_name' => 'Opinião',
          'posts_per_page' => 6,
          'paged' => $paged,   
          
        ));

      if ($boletim_query->have_posts()): ?>
      <div class="posts">
        <?php while ($boletim_query->have_posts()):$boletim_query->the_post(); ?>
        <div class="post">
          <div class="item">
            <?php if( CFS()->get('imagem') != '') {  ?>
            <img class="img-opniao" src="<?php echo esc_url(CFS()->get('imagem')); ?>"
              alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
            <?php }?>
            <div>
              <a href="<?php the_permalink(); ?>" class="read-more">
                <h2><?php the_title(); ?></h2>
                <span class="data">
                  <?php $data=strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>

                </span>
                <p>
                  <?php echo wp_trim_words(esc_html(CFS()->get('descricao') ?: get_the_excerpt()), 20, '...'); ?>
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

    <aside class="aside-info">
      <?php echo do_shortcode('[bm_banner id="399779"]');?>
      <h2>Boletins</h2>
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
  </div>
</div>

<?php endwhile;
else:
endif; ?>
<?php get_template_part('components/Footer/index'); ?>

<?php get_footer(); ?>
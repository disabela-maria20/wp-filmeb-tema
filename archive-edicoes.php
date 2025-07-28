<?php

get_header();
?>
<?php
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
<div class="container">
  <div class="grid-list-post-rapidinhas gap-124">
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
      $boletim_query = new WP_Query(array(
        'post_type' => 'edicoes',
        'posts_per_page' => 10,
      ));
      

      if ($boletim_query->have_posts()): ?>
      <h1>Edições anteriores</h1>
      <?php while ($boletim_query->have_posts()): $boletim_query->the_post(); ?>
      <div class="grid-semanal">
        <a href="<?php the_permalink(); ?>" class="link-post-semanal">
          <h2><?php the_title(); ?></h2>
        </a>

        <?php
        $values = CFS()->get('edicao');
        if (!empty($values) && is_array($values)) {
        $primeiros_dois = array_slice($values, 0, 3);
        foreach ($primeiros_dois as $post_id) {
            $the_post = get_post($post_id);
            ?>
        <a href="<?php echo str_replace("https://filmeb.isabelamribeiro.com.br", get_site_url(), $the_post->guid); ?>"
          class="link-lista-rapidinha">
          <?php if (has_post_thumbnail($the_post->ID)) : ?>
          <?php echo get_the_post_thumbnail($the_post->ID, 'thumbnail'); ?>
          <?php endif; ?>
          <span><?php echo $the_post->post_title; ?></span>
        </a>
        <?php } ?>
        <?php } ?>
      </div>
      <?php endwhile; ?>
      <div class="pagination">
        <?php
          echo paginate_links(array(
            'total' => $boletim_query->max_num_pages,
            'type' => 'list',
            'prev_text' => __('<'),
            'next_text' => __('>'),
            'mid_size' => 10,
          ));
          ?>
      </div>
      <?php else: ?>
      <p>Nenhuma rapidinha encontrada.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
    <aside class="aside-boletim">
      <?php echo do_shortcode('[bm_banner id="399753"]');?>
      <h2>Boletins</h2>
      <?php get_template_part('components/Aside/index'); ?>
    </aside>
  </div>



</div>
<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
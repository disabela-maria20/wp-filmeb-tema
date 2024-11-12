<?php
// Template Name: Rapidinhas
get_header();
?>
<a href="<?php echo CFS()->get('rapidinha_link_banner_moldura'); ?>" target=" _blank" rel="noopener noreferrer">
  <img src="<?php echo CFS()->get('rapidinha_banner_moudura'); ?>" class="img-banner bannerMobile" alt="banner">

</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo CFS()->get('rapidinha_link_banner_moldura'); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo CFS()->get('rapidinha_banner_moudura'); ?>" class="img-banner" alt="banner">
    </a>
    <a href="<?php echo CFS()->get('rapidinha_link_mega_banner'); ?>">
      <img src="<?php echo CFS()->get('rapidinha_mega_banner'); ?>" class="img-banner " alt="banner">
    </a>
  </div>
</div>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>

<div class="container">
  <div class="grid-list-post-rapidinhas gap-124">
    <div>
      <a href="<?php echo CFS()->get('rapidinha_link_super_banner'); ?>">
        <img src="<?php echo CFS()->get('rapidinha_super_banner'); ?>" class="img-banner" alt="banner">
      </a>


      <?php
          if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
          } ?>

      <?php
          $boletim_query = new WP_Query(array(
            'category_name' => 'Rapidinhas',
            'posts_per_page' => 10,
          ));

          if ($boletim_query->have_posts()): ?>
      <h1>Rapidinhas</h1>
      <div class="posts">
        <?php while ($boletim_query->have_posts()): $boletim_query->the_post(); ?>
        <div class="item-rapidinha">
          <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <div>
            <span class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></span>
            <a href="<?php the_permalink(); ?>" class="read-more">
              <h2><?php the_title(); ?></h2>
            </a>
            <a href="<?php the_permalink(); ?>">
              Leia mais
            </a>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
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
      <p>Nenhum boletim encontrado.</p>
      <?php endif; ?>
      <?php wp_reset_postdata(); ?>
    </div>
    <aside class="aside-info">
      <a href="<?php echo CFS()->get('rapidinha_link_skyscraper'); ?>">
        <img src="<?php echo CFS()->get('rapidinha_skyscraper'); ?>" class="img-banner" alt="banner">
      </a>

      <h2>Boletim da semana</h2>
      <?php
          $rapidinhas_id = get_cat_ID('Rapidinhas');

          $recent_posts_query = new WP_Query(array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            'orderby' => 'date',
            'order' => 'DESC',
            'category__in' => array($rapidinhas_id),
          ));

          if ($recent_posts_query->have_posts()) {
            while ($recent_posts_query->have_posts()) {
              $recent_posts_query->the_post(); ?>
      <div class="item-aside">
        <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
          alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
        <a href="<?php the_permalink(); ?>">
          <h3><?php echo esc_html(CFS()->get('titulo') ?: get_the_title()); ?></h3>
        </a>
      </div>
      <?php }
            wp_reset_postdata();
          } else {
            echo '<p>Nenhum post encontrado.</p>';
          }
          ?>
    </aside>
  </div>
</div>
<?php endwhile;
else: endif; ?>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
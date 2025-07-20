<?php
get_header();
?>

<?php
$banner_id = "78847";

$author_id = get_the_author_meta('ID');

$banner_superior = CFS()->get('banner_moldura', $banner_id);
$banner_inferior = CFS()->get('mega_banner', $banner_id);
$full_banner = CFS()->get('full_banner', $banner_id);
$skyscraper = CFS()->get('skyscraper', $banner_id);
$super_banner = CFS()->get('super_banner', $banner_id);
$modulo = CFS()->get('modulo', $banner_id);

$link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
$link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
$link_full_banner = CFS()->get('link_full_banner', $banner_id);
$link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
$link_super_banner = CFS()->get('link_super_banner', $banner_id);
$link_modulo = CFS()->get('link_modulo', $banner_id);

$big_stamp = CFS()->get('big_stamp', $banner_id);
$link_big_stampr = CFS()->get('link_big_stamp', $banner_id);


$boletim_query = new WP_Query(array(
  'category_name' => 'Rapidinhas',
  'posts_per_page' => 10,
));

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
  <div class="grid-list-post-rapidinhas gap-124">
    <div>
      <a href="<?php echo esc_url($link_full_banner); ?>">
        <img src="<?php echo esc_url($full_banner); ?>" class="img-banner" style="padding-bottom: 25px;" alt="banner">
      </a>

      <div id="breadcrumbs">
        <?php if ( function_exists('bcn_display') ) {
          bcn_display();
      } ?>
      </div>
      <?php if (has_post_thumbnail()): ?>
      <div class="post-thumbnail">
        <?php the_post_thumbnail('full'); ?>
      </div>
      <?php endif; ?>

      <div class="post-content">
        <strong class="data">
          <?php $data = strtotime(CFS()->get('data')); echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data); ?>
        </strong>
        <h1 class="opem"><?php echo get_post()->post_title;?></h1>
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
          <?php $id_rapidinha = get_the_ID(); ?>
          <?php the_content(); ?>
        </div>
      </div>
      <a href="<?php echo esc_url($link_modulo); ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($modulo); ?>" class="img-banner" alt="banner">
      </a>
      <div class="pagination">
        <?php echo paginate_links(array(
          'total' => $boletim_query->max_num_pages,
          'type' => 'list',
          'prev_text' => __('<'),
          'next_text' => __('>'),
          'mid_size' => 10,
        )); ?>
      </div>
    </div>
    <aside class="aside-info">
      <?php if( esc_url($skyscraper != '')) {  ?>
      <a href="<?php echo esc_url($link_skyscraper); ?>">
        <img src="<?php echo esc_url($skyscraper); ?>" class="img-banner" alt="banner">
      </a>
      <?php }?>
      <h2>Últimas Rapidinhas</h2>
      <?php
        $rapidinhas_posts_query = new WP_Query(array(
          'post_type' => 'rapidinhas',
          'posts_per_page' => 8,
        ));
        ?>

      <?php if ($rapidinhas_posts_query->have_posts()) : ?>
      <?php while ($rapidinhas_posts_query->have_posts()) : $rapidinhas_posts_query->the_post(); ?>
      <div class="item-rapidinha">
        <?php if (esc_url(CFS()->get('imagem')) != '') : ?>
        <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
          alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
        <?php endif; ?>
        <div>
          <h3><?php echo get_the_title(); ?></h3>
          <span class="data">
            <?php
            $data = strtotime(CFS()->get('data'));
            echo date('j', $data) . ' ' . mb_substr(strtolower(date_i18n('F', $data)), 0, 3) . ' ' . date('Y', $data);
          ?>
          </span>
          <a href="<?php the_permalink(); ?>">Leia mais</a>
        </div>
      </div>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
      <?php else : ?>
      <p>Nenhuma rapidinha encontrada.</p>
      <?php endif; ?>
    </aside>
  </div>
</div>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
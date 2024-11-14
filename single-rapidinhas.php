<?php 
get_header();
?>

<a href="<?php echo CFS()->get('rapidinha_link_banner_moldura'); ?>" target="_blank" rel="noopener noreferrer">
  <img src="<?php echo CFS()->get('rapidinha_banner_moudura'); ?>" class="img-banner bannerMobile" alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo CFS()->get('rapidinha_link_banner_moldura'); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo CFS()->get('rapidinha_banner_moudura'); ?>" class="img-banner" alt="banner">
    </a>
    <a href="<?php echo CFS()->get('rapidinha_link_mega_banner'); ?>">
      <img src="<?php echo CFS()->get('rapidinha_mega_banner'); ?>" class="img-banner" alt="banner">
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

      <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-boletim-filme-b-horizontal.png"
        class="logo" alt="cine B" />
      <?php
          if (function_exists('yoast_breadcrumb')) {
            yoast_breadcrumb('<div id="breadcrumbs">', '</div>');
          } ?>

      <section class="post">
        <div>
          <strong class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></strong>
          <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
            alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
          <a href="" class="autor">
            <img src="<?php echo get_avatar_url($author_id) ?>"
              alt="<?php get_the_author_meta('display_name', $author_id) ?>">
          </a>
        </div>
        <div class="post-content">
          <h1><?php the_title(); ?></h1>
          <?php if (has_post_thumbnail()): ?>
          <div class="post-thumbnail">
            <?php the_post_thumbnail('large'); ?>
          </div>
          <?php endif; ?>
          <div class="post-text">
            <?php the_content(); ?>
          </div>
        </div>
      </section>
    </div>
    <aside class="aside-info">
      <!-- Conteúdo do aside -->
    </aside>
  </div>
</div>

<?php endwhile; else: ?>
<p>Nenhum post encontrado na categoria Rapidinhas.</p>
<?php endif; ?>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
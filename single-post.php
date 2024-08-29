<img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner bannerMobile" alt="banner">

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <img src="<?php echo CFS()->get('banner_superior'); ?>" class="img-banner" alt="banner">
    <img src="<?php echo CFS()->get('banner_inferior'); ?>" class="img-banner " alt="banner">
  </div>
</div>


<?php
get_header();
?>
post
<?php if (have_posts()): ?>
  <?php while (have_posts()): the_post(); ?>
  
    <div class="post-content">
      <!-- Título do Post -->
      <h1><?php the_title(); ?></h1>

      <!-- Imagem Destacada -->
      <?php if (has_post_thumbnail()): ?>
        <div class="post-thumbnail">
          <?php the_post_thumbnail('large'); ?>
        </div>
      <?php endif; ?>

      <!-- Conteúdo do Post -->
      <div class="post-text">
        <?php the_content(); ?>
      </div>
    </div>

  <?php endwhile; ?>
<?php else: ?>
  <p>Nenhum post encontrado.</p>
<?php endif; ?>

<?php get_footer(); ?>

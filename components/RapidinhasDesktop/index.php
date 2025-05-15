<?php
$recent_posts_query = new WP_Query(array(
  'post_type' => 'rapidinhas',
  'posts_per_page' => 10,
  'orderby' => 'date',
  'order' => 'DESC'
));

if ($recent_posts_query->have_posts()) {
  while ($recent_posts_query->have_posts()) {
    $recent_posts_query->the_post(); ?>
<div class="item-rapidinha">
  <?php if (esc_url(CFS()->get('imagem')) != '') {  ?>
  <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
    alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
  <?php } ?>
  <div>
    <span class="data">
      <h3><?php echo get_post()->post_title;?></h3>
      <span> <?php echo strtolower(date_i18n('j M Y', strtotime(CFS()->get('data')))); ?></span>
      <a href="<?php the_permalink(); ?>">Leia mais</a>
  </div>
</div>
<?php
  }
  wp_reset_postdata();
} else {
  echo '<p>Nenhum post encontrado.</p>';
}
?>
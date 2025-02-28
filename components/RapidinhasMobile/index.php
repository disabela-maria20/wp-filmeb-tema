<?php

$rapidinhas_posts_query = new WP_Query(array(
  'post_type' => 'rapidinhas',
  'posts_per_page' => 10,
));

if ($rapidinhas_posts_query->have_posts()) {
  $post_count = 0;

  while ($rapidinhas_posts_query->have_posts()) {
    $rapidinhas_posts_query->the_post();


    if ($post_count % 3 == 0) {
      if ($post_count > 0) {
        echo '</div>'; // Fecha a div.grid anterior
        echo '</div>'; // Fecha a div.item anterior
      }
      echo '<div class="item"><div class="grid grid-1-lg gap-32">';
    }

?>
<div class="item-rapidinha">
  <?php if( esc_url(CFS()->get('imagem')) != '') {  ?>
  <img src="<?php echo esc_url(CFS()->get('imagem')); ?>"
    alt="<?php echo esc_attr(CFS()->get('titulo') ?: get_the_title()); ?>" />
  <?php }?>
  <div>
    <span class="data"><?php echo date_i18n('j \d\e F \d\e Y', strtotime(get_the_date())); ?></span>
    <h3><?php  echo get_the_title();?></h3>
    <a href="<?php  the_permalink(); ?>">Leia mais</a>
  </div>
</div>
<?php

    $post_count++;
  }

  // Fecha a última div.item
  echo '</div>'; // Fecha a div.grid
  echo '</div>'; // Fecha a div.item

  wp_reset_postdata();
} else {
  echo '<p>Nenhum post encontrado.</p>';
}
?>
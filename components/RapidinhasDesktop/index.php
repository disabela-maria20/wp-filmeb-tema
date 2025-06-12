<?php
$recent_posts_query = new WP_Query(array(
  'post_type' => 'rapidinhas',
  'posts_per_page' => 8,
  'orderby' => 'date',
  'order' => 'DESC'
));

if ($recent_posts_query->have_posts()) {
  while ($recent_posts_query->have_posts()) {
    $recent_posts_query->the_post(); 
    $image_url = CFS()->get('imagem');
    $title = CFS()->get('titulo') ?: get_the_title();
    $post_date = CFS()->get('data');
    ?>
<div class="item-rapidinha">
  <?php if (!empty($image_url)) {  ?>
  <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($title); ?>" />
  <?php } ?>
  <div>
    <span class="data">
      <h3><?php echo esc_html(get_the_title()); ?></h3>
      <span>
        <?php 
        if (!empty($post_date)) {
          $data = strtotime($post_date); 
          if ($data !== false) {
            echo date('j', $data).' '.mb_substr(strtolower(date_i18n('F', $data)), 0, 3).' '.date('Y', $data);
          }
        }
        ?>
      </span>
      <a href="<?php the_permalink(); ?>">Leia mais</a>
    </span>
  </div>
</div>
<?php
  }
  wp_reset_postdata();
} else {
  echo '<p>Nenhum post encontrado.</p>';
}
?>
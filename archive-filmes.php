<?php get_header(); ?>

<?php
$banner_id = "77483";

$args = array(
  'post_type' => 'banner-post',
  'posts_per_page' => 1,
);

$query = new WP_Query($args);

$termos = get_terms(array(
  'taxonomy' => 'generos',
  'hide_empty' => false,
));

$tecnologias = get_terms(array(
  'taxonomy' => 'tecnologias',
  'hide_empty' => false,
));

$distribuidoras = get_terms(array(
  'taxonomy' => 'distribuidoras',
  'hide_empty' => false,
));

$paises = get_terms(array(
  'taxonomy' => 'paises',
  'hide_empty' => false,
));

$meses = [
  '01' => 'Janeiro',
  '02' => 'Fevereiro',
  '03' => 'Março',
  '04' => 'Abril',
  '05' => 'Maio',
  '06' => 'Junho',
  '07' => 'Julho',
  '08' => 'Agosto',
  '09' => 'Setembro',
  '10' => 'Outubro',
  '11' => 'Novembro',
  '12' => 'Dezembro',
];

$dias_semana = [
  'Sunday'    => 'Domingo',
  'Monday'    => 'Segunda-feira',
  'Tuesday'   => 'Terça-feira',
  'Wednesday' => 'Quarta-feira',
  'Thursday'  => 'Quinta-feira',
  'Friday'    => 'Sexta-feira',
  'Saturday'  => 'Sábado',
];

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
  'post_type' => 'filmes',
  'posts_per_page' => 10,
  'post_status' => 'publish',
  'paged' => $paged,
);

// Aplicar filtros
if (isset($_GET['ano']) && !empty($_GET['ano'])) {
  $args['meta_query'][] = array(
    'key' => 'estreia',
    'value' => sanitize_text_field($_GET['ano']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['mes']) && !empty($_GET['mes'])) {
  $args['meta_query'][] = array(
    'key' => 'estreia',
    'value' => sanitize_text_field($_GET['mes']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['origem']) && !empty($_GET['origem'])) {
  $args['meta_query'][] = array(
    'key' => 'paises',
    'value' => sanitize_text_field($_GET['origem']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['distribuicao']) && !empty($_GET['distribuicao'])) {
  $args['meta_query'][] = array(
    'key' => 'distribuicao',
    'value' => sanitize_text_field($_GET['distribuicao']),
    'compare' => '=',
  );
}

if (isset($_GET['genero']) && !empty($_GET['genero'])) {
  $args['meta_query'][] = array(
    'key' => 'generos',
    'value' => sanitize_text_field($_GET['genero']),
    'compare' => 'REGEXP',
  );
}

if (isset($_GET['tecnologia']) && !empty($_GET['tecnologia'])) {
  $args['meta_query'][] = array(
    'key' => 'tecnologia',
    'value' => sanitize_text_field($_GET['tecnologia']),
    'compare' => 'REGEXP',
  );
}

$filmes = new WP_Query($args);

// Array para armazenar filmes agrupados por dia

if ($filmes->have_posts()) {
  while ($filmes->have_posts()) {
    $filmes->the_post();
  }
}

function render_terms($field_key, $post_id)
{
  $distribuicao = CFS()->get($field_key, $post_id);
  $output = '';
  if (!empty($distribuicao)) {
    foreach ($distribuicao as $term_id) {
      $term = get_term($term_id);
      if ($term && !is_wp_error($term)) {
        $output .= '<div>' . esc_html($term->name) . '</div>';
      }
    }
  }
  return $output;
}
?>

<?php if ($query->have_posts()): ?>
<?php while ($query->have_posts()): $query->the_post(); ?>
<?php
    $banner_lateral = CFS()->get('banner_lateral', $banner_id);

    $banner_superior = CFS()->get('banner_moldura', $banner_id);
    $banner_inferior = CFS()->get('mega_banner', $banner_id);
    $skyscraper = CFS()->get('skyscraper', $banner_id);
    $big_stamp = CFS()->get('big_stamp', $banner_id);
    $banner_moldura_casado = CFS()->get('banner_moldura_casado', $banner_id);

    $link_banner_superior = CFS()->get('link_banner_moldura', $banner_id);
    $link_banner_inferior = CFS()->get('link_mega_banner', $banner_id);
    $link_skyscraper = CFS()->get('link_skyscraper', $banner_id);
    $link_big_stampr = CFS()->get('link_big_stamp', $banner_id);
    $link_banner_moldura_casado = CFS()->get('link_banner_moldura_casado', $banner_id);
    ?>
<a href="<?php echo esc_url($link_banner_superior) ?>" target="_blank" rel="noopener noreferrer">
  <img src="<?php echo esc_url($banner_superior); ?>" class="w-full p-35 img-banner bannerMobile " alt="banner">
</a>

<div class="container bannerDesktop">
  <div class="grid-banner-superior">
    <a href="<?php echo esc_url($link_banner_inferior); ?>" target="_blank" rel="noopener noreferrer">
      <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
    </a>
  </div>
</div>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
<?php endif; ?>

<?php get_template_part('components/MenuMobile/index'); ?>
<?php get_template_part('components/MenuDesktop/index'); ?>

<section class="bg-gray padding-banner">
  <div class="container bannerMobile">
    <div class="grid-banner-superior">
      <a href="<?php echo $link_banner_inferior; ?>" target="_blank" rel="noopener noreferrer">
        <img src="<?php echo esc_url($banner_inferior); ?>" class="img-banner" alt="banner">
      </a>
    </div>
  </div>
</section>

<div class="container page-filmes">

</div>

<?php get_template_part('components/Footer/index'); ?>
<?php get_footer(); ?>
<?php

require_once(get_template_directory() . "/api/banner.php");
require_once(get_template_directory() . "/api/filmes.php");

add_filter('wp_image_editors', 'wpb_image_editor_default_to_gd');

function wpb_image_editor_default_to_gd($editors)
{
  $gd_editor = 'WP_Image_Editor_GD';
  $editors = array_diff($editors, array($gd_editor));
  array_unshift($editors, $gd_editor);
  return $editors;
}

function use_scripts()
{
  wp_enqueue_style('base-style', get_template_directory_uri() . '/assets/css/base.min.css', array(), '1.0.0', 'all');
  wp_enqueue_script('jquery');
  wp_enqueue_script('owl-carousel', get_template_directory_uri() . '/assets/js/lib/owl.carousel.min.js', array('jquery'), '2.3.4', true);
  wp_enqueue_script('splide', get_template_directory_uri() . '/assets/js/lib/splide.min.js', array('jquery'), '2.3.4', true);
  wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap', array(), null, 'all');
  wp_enqueue_style('bootstrap-icons', 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css', array(), null, 'all');

  wp_enqueue_script('main-script', get_template_directory_uri() . '/assets/js/main.js', array('jquery', 'owl-carousel'), null, true);
}
add_action('wp_enqueue_scripts', 'use_scripts');

function add_meta_tags()
{
  echo '<meta name="viewport" content="width=device-width, initial-scale=1.0" />';
}
add_action('wp_head', 'add_meta_tags');

add_theme_support('menus');

function register_my_menu()
{
  register_nav_menu('menu-principal', __('Menu Principal'));
  register_nav_menu('institucional', __('Menu Institucional'));
}
add_action('init', 'register_my_menu');



function flush_rewrite_rules_on_theme_activation()
{
  flush_rewrite_rules();
}
add_action('after_switch_theme', 'flush_rewrite_rules_on_theme_activation');


function remove_tablepress_default_css() {
  wp_deregister_style('tablepress-default');
}
add_action('wp_enqueue_scripts', 'remove_tablepress_default_css', 20);


function custom_breadcrumb_category($links) {
  if (is_single() && in_category('Rapidinhas')) {
      foreach ($links as &$link) {
          if ($link['text'] === 'Notícias') {
              $link['text'] = 'Rapidinhas';
              $link['url'] = get_category_link(get_cat_ID('Rapidinhas'));
          }
      }
  }
  return $links;
}
add_filter('wpseo_breadcrumb_links', 'custom_breadcrumb_category');


function registrar_cpt_filmes()
{
    register_post_type('filmes', array(
        'labels' => array(
            'name'               => _x('Filmes', 'Post type general name', 'textdomain'),
            'singular_name'      => _x('Filme', 'Post type singular name', 'textdomain'),
            'add_new_item'       => __('Adicionar Novo Filme', 'textdomain'),
            'edit_item'          => __('Editar Filme', 'textdomain'),
            'new_item'           => __('Novo Filme', 'textdomain'),
            'view_item'          => __('Ver Filme', 'textdomain'),
            'search_items'       => __('Buscar Filmes', 'textdomain'),
            'not_found'          => __('Nenhum Filme encontrado', 'textdomain'),
            'not_found_in_trash' => __('Nenhum Filme encontrado na lixeira', 'textdomain'),
        ),
        'description'        => 'Gerenciar Filmes',
        'public'             => true,
        'show_ui'            => true,
        'menu_position'      => 5,
        'menu_icon'          => 'dashicons-video-alt',
        'capability_type'    => 'post',
        'rewrite'            => array('slug' => 'filmes', 'with_front' => true),
        'supports'           => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive'        => true,
        'publicly_queryable' => true,
    ));
}
add_action('init', 'registrar_cpt_filmes');


function formatar_data_estreia($estreia, $mostrar_dia_da_semana = false) {
  $data = CFS()->get($estreia);

  if (empty($data)) {
      echo '<p>Campo de data vazio ou inexistente</p>';
      return;
  }

  $timestamp = strtotime($data);

  if ($timestamp === false) {
      echo '<p>Formato de data inválido: ' . esc_html($data) . '</p>';
      return;
  }

  $data_formatada = date_i18n('d \d\e F \d\e Y', $timestamp);

  if ($mostrar_dia_da_semana) {
      $dia_da_semana = date_i18n('l', $timestamp);
      $data_formatada = $dia_da_semana . ', ' . $data_formatada;
  }

  echo '<p>' . esc_html($data_formatada) . '</p>';
}


add_action('wp_ajax_filtrar_filmes', 'filtrar_filmes');
add_action('wp_ajax_nopriv_filtrar_filmes', 'filtrar_filmes');

function filtrar_filmes() {
  $mes = $_POST['mes'] ?? '';
  $distribuidora = $_POST['distribuidora'] ?? '';
  $genero = $_POST['genero'] ?? '';

  $args = [
    'post_type' => 'filmes',
    'posts_per_page' => -1,
    'tax_query' => []
  ];

  if ($genero) {
    $args['tax_query'][] = ['taxonomy' => 'generos', 'field' => 'term_id', 'terms' => $genero];
  }
  if ($distribuidora) {
    $args['tax_query'][] = ['taxonomy' => 'distribuidoras', 'field' => 'term_id', 'terms' => $distribuidora];
  }

  $query = new WP_Query($args);

  if ($query->have_posts()) {
    while ($query->have_posts()) {
      $query->the_post();
      echo '<div class="card">';
      echo '<h3>' . get_the_title() . '</h3>';
      echo '</div>';
    }
  } else {
    echo '<p>Nenhum filme encontrado.</p>';
  }
  wp_die();
}
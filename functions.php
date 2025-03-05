<?php

function handel_add_woocommerce_support()
{
  add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'handel_add_woocommerce_support');


require_once(get_template_directory() . "/api/banner.php");
require_once(get_template_directory() . "/api/filmes.php");
require_once(get_template_directory() . "/api/distribuidora.php");
require_once(get_template_directory() . "/api/rapidinhas.php");
require_once(get_template_directory() . "/api/noticias.php");
require_once(get_template_directory() . "/api/usuario.php");


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
  if (!is_page('filmes')) {
    wp_enqueue_style('base-style', get_template_directory_uri() . '/assets/css/base.min.css', array(), '1.0.0', 'all');
  }

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


function remove_tablepress_default_css()
{
  wp_deregister_style('tablepress-default');
}
add_action('wp_enqueue_scripts', 'remove_tablepress_default_css', 20);


function custom_breadcrumb_category($links)
{
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

function formatar_data_estreia($estreia, $mostrar_dia_da_semana = false)
{
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


function upload_image_from_url($image_url)
{
  require_once ABSPATH . 'wp-admin/includes/file.php';
  require_once ABSPATH . 'wp-admin/includes/media.php';
  require_once ABSPATH . 'wp-admin/includes/image.php';

  $temp_file = download_url($image_url);

  if (is_wp_error($temp_file)) {
    return $temp_file;
  }

  $file_array = [
    'name' => basename(parse_url($image_url, PHP_URL_PATH)),
    'tmp_name' => $temp_file
  ];

  $attachment_id = media_handle_sideload($file_array, 0);

  if (is_wp_error($attachment_id)) {
    @unlink($temp_file);
    return $attachment_id;
  }

  return $attachment_id;
}

function obter_term_ids($itens, $taxonomia)
{
  $ids = [];

  foreach ($itens as $item) {
    if (is_numeric($item)) {

      $ids[] = intval($item);
    } else {

      $term = get_term_by('name', $item, $taxonomia);

      if ($term && !is_wp_error($term)) {

        $ids[] = $term->term_id;
      } else {

        error_log('Termo não encontrado para ' . $taxonomia . ': ' . $item);
      }
    }
  }

  return $ids;
}


function custom_cors_headers()
{
  header("Access-Control-Allow-Origin: http://127.0.0.1:5500");
  header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
  header("Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token");

  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("HTTP/1.1 200 OK");
    exit();
  }
}

add_action('init', 'custom_cors_headers');

function formatar_data_personalizada($texto) {
 
  if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $texto, $matches)) {
      $dia = $matches[1];
      $mes = (int)$matches[2];
      $ano = $matches[3];

     
      $meses = [
          1 => 'janeiro', 2 => 'fevereiro', 3 => 'março', 4 => 'abril',
          5 => 'maio', 6 => 'junho', 7 => 'julho', 8 => 'agosto',
          9 => 'setembro', 10 => 'outubro', 11 => 'novembro', 12 => 'dezembro'
      ];

     
      $dataFormatada = sprintf('%s de %s de %s', $dia, $meses[$mes], $ano);

     
      return preg_replace('/\d{2}-\d{2}-\d{4}/', $dataFormatada, $texto);
  }

  return $texto;
}

add_filter('the_title', 'formatar_data_personalizada');

add_filter('the_content', 'formatar_data_personalizada');

function custom_archive_filmes_title($title) {
  if (is_post_type_archive('filmes')) {
      $title = 'Lista de Filmes - ' . get_bloginfo('name');
  }
  return $title;
}
add_filter('pre_get_document_title', 'custom_archive_filmes_title');


// Usuarios associados

function meu_dashboard() {
  echo 'Origamid';
}

add_action('woocommerce_account_dashboard', 'meu_dashboard');


function personalizar_texto_pagseguro($translated_text, $text, $domain) {
  if ($domain === 'woocommerce-pagseguro') {
      if ($text === 'Pagar com PagSeguro') {
          return 'Pague com segurança via PagSeguro';
      }
  }
  return $translated_text;
}
add_filter('gettext', 'personalizar_texto_pagseguro', 20, 3);
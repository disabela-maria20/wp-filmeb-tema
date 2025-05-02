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
require_once(get_template_directory() . "/api/taxionomia.php");
require_once(get_template_directory() . "/inc/woocommerce.php");

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

  wp_enqueue_style('lite-yt-embed', 'https://cdnjs.cloudflare.com/ajax/libs/lite-youtube-embed/0.3.3/lite-yt-embed.min.css', array(), '1.0.0', 'all');

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


function remove_tablepress_default_css()
{
  wp_deregister_style('tablepress-default');
}
add_action('wp_enqueue_scripts', 'remove_tablepress_default_css', 20);


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

function formatar_data_personalizada($texto)
{

  if (preg_match('/(\d{2})-(\d{2})-(\d{4})/', $texto, $matches)) {
    $dia = $matches[1];
    $mes = (int)$matches[2];
    $ano = $matches[3];


    $meses = [
      1 => 'janeiro',
      2 => 'fevereiro',
      3 => 'março',
      4 => 'abril',
      5 => 'maio',
      6 => 'junho',
      7 => 'julho',
      8 => 'agosto',
      9 => 'setembro',
      10 => 'outubro',
      11 => 'novembro',
      12 => 'dezembro'
    ];


    $dataFormatada = sprintf('%s de %s de %s', $dia, $meses[$mes], $ano);


    return preg_replace('/\d{2}-\d{2}-\d{4}/', $dataFormatada, $texto);
  }

  return $texto;
}

add_filter('the_title', 'formatar_data_personalizada');

add_filter('the_content', 'formatar_data_personalizada');





function format_products($products, $img_size = 'medium')
{
  $products_final = [];
  foreach ($products as $product) {
    $products_final[] = [
      'name' => $product->get_name(),
      'price' => $product->get_price_html(),
      'link' => $product->get_permalink(),
      'img' => wp_get_attachment_image_src($product->get_image_id(), $img_size)[0],
    ];
  }
  return $products_final;
}


function redirecionar_para_checkout()
{
  // URL da página de checkout
  return wc_get_checkout_url();
}
add_filter('woocommerce_add_to_cart_redirect', 'redirecionar_para_checkout');


add_action('template_redirect', 'redirecionar_assinatura_filme_b');
function redirecionar_assinatura_filme_b()
{
  if (is_singular('product') && strpos($_SERVER['REQUEST_URI'], '/produto/assinatura-filme-b/') !== false) {
    wp_redirect(home_url('/assine/'), 301);
    exit;
  }

}

function remover_mensagem_padrao_sem_pedidos()
{
  if (is_wc_endpoint_url('orders')) {
    remove_action('woocommerce_account_orders_endpoint', 'woocommerce_account_orders_content', 10);
  }
}
add_action('template_redirect', 'remover_mensagem_padrao_sem_pedidos');

add_action('wp_ajax_add_to_cart_ajax', 'add_to_cart_ajax');
add_action('wp_ajax_nopriv_add_to_cart_ajax', 'add_to_cart_ajax');

function add_to_cart_ajax() {
    if (!isset($_GET['product_id'])) {
        wp_send_json(['success' => false, 'message' => 'ID do produto ausente']);
    }

    $product_id = intval($_GET['product_id']);

    if (WC()->cart) {
        WC()->cart->add_to_cart($product_id);
        wp_send_json(['success' => true]);
    } else {
        wp_send_json(['success' => false, 'message' => 'Carrinho não disponível']);
    }
}

function extrair_texto_apos_traco($texto) {
    return $texto;
}

function get_thursday_movies() {
  $today = new DateTime();
  
  $next_thursday = clone $today;
  while ($next_thursday->format('N') != 4) { 
      $next_thursday->modify('+1 day');
  }

  if ($today->format('Y-m-d') == $next_thursday->format('Y-m-d')) {
      $next_thursday->modify('+7 days');
  }

  $next_thursday_date = $next_thursday->format('Y-m-d');

  return new WP_Query(array(
      'post_type' => 'filmes',
      'posts_per_page' => -1,
      'meta_key' => 'estreia',
      'orderby' => 'meta_value',
      'order' => 'ASC',
      'post_status' => 'publish',
      'meta_query' => array(
          array(
              'key' => 'estreia',
              'value' => $next_thursday_date,
              'compare' => '=',
              'type' => 'DATE'
          )
      )
  ));
}


// Shortcode para mostrar apenas o formulário de cadastro do WooCommerce
function custom_woocommerce_registration_form() {
  if ( is_user_logged_in() ) return '<p>Você já está logado.</p>';

  ob_start();
  do_action( 'woocommerce_before_customer_login_form' );

  ?>
<div class="u-columns col2-set" id="customer_login">
  <div class="u-column2 col-2">
    <h2>Criar Conta</h2>
    <?php
          do_action( 'woocommerce_register_form_start' );

          woocommerce_register_form();

          do_action( 'woocommerce_register_form_end' );
          ?>
  </div>
</div>
<?php

  return ob_get_clean();
}
add_shortcode( 'custom_woocommerce_register', 'custom_woocommerce_registration_form' );
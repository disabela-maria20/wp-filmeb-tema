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

require_once(get_template_directory() . '/inc/checkout-customizado.php');

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

function registrar_cpt_filmes() {
  register_post_type('filmes', array(
      'labels' => array(
          'name' => _x('Filmes', 'Post type general name', 'textdomain'),
          'singular_name' => _x('Filme', 'Post type singular name', 'textdomain'),
          'add_new_item' => __('Adicionar Novo Filme', 'textdomain'),
          'edit_item' => __('Editar Filme', 'textdomain'),
          'new_item' => __('Novo Filme', 'textdomain'),
          'view_item' => __('Ver Filme', 'textdomain'),
          'search_items' => __('Buscar Filmes', 'textdomain'),
          'not_found' => __('Nenhum Filme encontrado', 'textdomain'),
          'not_found_in_trash' => __('Nenhum Filme encontrado na lixeira', 'textdomain'),
      ),
      'description' => 'Gerenciar Filmes',
      'public' => true,
      'show_ui' => true,
      'menu_position' => 5,
      'menu_icon' => 'dashicons-video-alt',
      'capability_type' => 'post',
      'rewrite' => array('slug' => 'filmes', 'with_front' => true),
      'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
      'has_archive' => true,
      'publicly_queryable' => true,
      'show_in_rest' => true,
      'taxonomies' => array('distribuidoras', 'paises', 'generos', 'classificacoes', 'tecnologias', 'feriados'),
  ));
}
add_action('init', 'registrar_cpt_filmes');

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


// 1. Adicionar campos personalizados ao formulário de registro
add_action('woocommerce_register_form_start', 'add_custom_fields_to_registration');

function add_custom_fields_to_registration() {
    ?>
<p class="form-row form-row-wide">
  <label for="reg_billing_first_name"><?php _e('Nome *', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name"
    value="<?php if (!empty($_POST['billing_first_name'])) esc_attr_e($_POST['billing_first_name']); ?>" required />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_last_name"><?php _e('Sobrenome', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name"
    value="<?php if (!empty($_POST['billing_last_name'])) esc_attr_e($_POST['billing_last_name']); ?>" />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_categoria_profissional"><?php _e('Categoria Profissional *', 'woocommerce'); ?></label>
  <select name="billing_categoria_profissional" id="reg_billing_categoria_profissional" class="form-select required"
    required>
    <option value="_none">- Selecione um valor -</option>
    <option value="1">Advogado</option>
    <option value="2">Agência</option>
    <option value="3">Assessoria imprensa</option>
    <option value="4">Banco</option>
    <option value="5">Cineasta</option>
    <option value="6">Corretora</option>
    <option value="7">Distribuidor</option>
    <option value="8">Estudante</option>
    <option value="9">Exibidor</option>
    <option value="10">Exibidor-distribuidor</option>
    <option value="11">Festival</option>
    <option value="12">Imprensa</option>
    <option value="13">Infraestrutura</option>
    <option value="14">Investidor</option>
    <option value="15">Mercado</option>
    <option value="16">Órgão público</option>
    <option value="17">Portal internet</option>
    <option value="18">Produtor</option>
    <option value="19">Professor</option>
    <option value="20">Roteirista</option>
    <option value="21">Shopping</option>
    <option value="22">TV</option>
    <option value="23">Universidade</option>
    <option value="24">Vídeo</option>
    <option value="25">Outros</option>
  </select>
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_cpf_cnpj"><?php _e('CPF/CNPJ *', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_cpf_cnpj" id="reg_billing_cpf_cnpj"
    value="<?php if (!empty($_POST['billing_cpf_cnpj'])) esc_attr_e($_POST['billing_cpf_cnpj']); ?>" required />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_phone"><?php _e('Telefone', 'woocommerce'); ?></label>
  <input type="tel" class="input-text" name="billing_phone" id="reg_billing_phone"
    value="<?php if (!empty($_POST['billing_phone'])) esc_attr_e($_POST['billing_phone']); ?>" />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_cellphone"><?php _e('Celular *', 'woocommerce'); ?></label>
  <input type="tel" class="input-text" name="billing_cellphone" id="reg_billing_cellphone"
    value="<?php if (!empty($_POST['billing_cellphone'])) esc_attr_e($_POST['billing_cellphone']); ?>" required />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_address"><?php _e('Endereço', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_address" id="reg_billing_address"
    value="<?php if (!empty($_POST['billing_address'])) esc_attr_e($_POST['billing_address']); ?>" />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_complemento"><?php _e('Complemento', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_complemento" id="reg_billing_complemento"
    value="<?php if (!empty($_POST['billing_complemento'])) esc_attr_e($_POST['billing_complemento']); ?>" />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_bairro"><?php _e('Bairro', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_bairro" id="reg_billing_bairro"
    value="<?php if (!empty($_POST['billing_bairro'])) esc_attr_e($_POST['billing_bairro']); ?>" />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_city"><?php _e('Cidade', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_city" id="reg_billing_city"
    value="<?php if (!empty($_POST['billing_city'])) esc_attr_e($_POST['billing_city']); ?>" />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_state"><?php _e('Estado', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_state" id="reg_billing_state"
    value="<?php if (!empty($_POST['billing_state'])) esc_attr_e($_POST['billing_state']); ?>" />
</p>

<p class="form-row form-row-wide">
  <label for="reg_billing_postcode"><?php _e('CEP', 'woocommerce'); ?></label>
  <input type="text" class="input-text" name="billing_postcode" id="reg_billing_postcode"
    value="<?php if (!empty($_POST['billing_postcode'])) esc_attr_e($_POST['billing_postcode']); ?>" />
</p>
<?php
}

// 2. Validar campos obrigatórios
add_action('woocommerce_register_post', 'validate_custom_fields', 10, 3);

function validate_custom_fields($username, $email, $validation_errors) {
    if (isset($_POST['billing_first_name']) && empty($_POST['billing_first_name'])) {
        $validation_errors->add('billing_first_name_error', __('O campo Nome é obrigatório!', 'woocommerce'));
    }
    if (isset($_POST['billing_categoria_profissional']) && empty($_POST['billing_categoria_profissional'])) {
        $validation_errors->add('billing_categoria_profissional_error', __('O campo Categoria Profissional é obrigatório!', 'woocommerce'));
    }
    if (isset($_POST['billing_cpf_cnpj']) && empty($_POST['billing_cpf_cnpj'])) {
        $validation_errors->add('billing_cpf_cnpj_error', __('O campo CPF/CNPJ é obrigatório!', 'woocommerce'));
    }
    if (isset($_POST['billing_cellphone']) && empty($_POST['billing_cellphone'])) {
        $validation_errors->add('billing_cellphone_error', __('O campo Celular é obrigatório!', 'woocommerce'));
    }
    return $validation_errors;
}

// 3. Salvar campos personalizados no banco de dados
add_action('woocommerce_created_customer', 'save_custom_fields');

function save_custom_fields($customer_id) {
    if (isset($_POST['billing_first_name'])) {
        update_user_meta($customer_id, 'billing_first_name', sanitize_text_field($_POST['billing_first_name']));
        update_user_meta($customer_id, 'first_name', sanitize_text_field($_POST['billing_first_name']));
    }
    if (isset($_POST['billing_last_name'])) {
        update_user_meta($customer_id, 'billing_last_name', sanitize_text_field($_POST['billing_last_name']));
        update_user_meta($customer_id, 'last_name', sanitize_text_field($_POST['billing_last_name']));
    }
    if (isset($_POST['billing_categoria_profissional'])) {
        update_user_meta($customer_id, 'billing_categoria_profissional', sanitize_text_field($_POST['billing_categoria_profissional']));
    }
    if (isset($_POST['billing_cpf_cnpj'])) {
        update_user_meta($customer_id, 'billing_cpf_cnpj', sanitize_text_field($_POST['billing_cpf_cnpj']));
    }
    if (isset($_POST['billing_phone'])) {
        update_user_meta($customer_id, 'billing_phone', sanitize_text_field($_POST['billing_phone']));
    }
    if (isset($_POST['billing_cellphone'])) {
        update_user_meta($customer_id, 'billing_cellphone', sanitize_text_field($_POST['billing_cellphone']));
    }
    if (isset($_POST['billing_address'])) {
        update_user_meta($customer_id, 'billing_address', sanitize_text_field($_POST['billing_address']));
    }
    if (isset($_POST['billing_complemento'])) {
        update_user_meta($customer_id, 'billing_complemento', sanitize_text_field($_POST['billing_complemento']));
    }
    if (isset($_POST['billing_bairro'])) {
        update_user_meta($customer_id, 'billing_bairro', sanitize_text_field($_POST['billing_bairro']));
    }
    if (isset($_POST['billing_city'])) {
        update_user_meta($customer_id, 'billing_city', sanitize_text_field($_POST['billing_city']));
    }
    if (isset($_POST['billing_state'])) {
        update_user_meta($customer_id, 'billing_state', sanitize_text_field($_POST['billing_state']));
    }
    if (isset($_POST['billing_postcode'])) {
        update_user_meta($customer_id, 'billing_postcode', sanitize_text_field($_POST['billing_postcode']));
    }
}


// function restringir_acesso_membros_pagos() {
//   // if (is_tax('product_cat') || is_post_type_archive('rapidinhas')) {
//   //     if (!SwpmMemberUtils::is_member_logged_in()) { 
//   //         wp_redirect(home_url('/assine')); 
//   //         exit;
//   //     }
//   // }
//   $membership_level = SwpmMemberUtils::get_logged_in_members_level();
//   if ($membership_level != 2) { 
//     wp_redirect(home_url('/carrinho')); 
//     exit;
//   }
// }
// add_action('template_redirect', 'restringir_acesso_membros_pagos');


function handel_custom_menu($menu_links) {
  $menu_links = array_slice($menu_links, 0, 5, true)
  + ['assinaturas' => 'Assinaturas']
  + array_slice($menu_links, 3, NULL, true);

  unset($menu_links['downloads']);
  return $menu_links;
}
add_filter('woocommerce_account_menu_items', 'handel_custom_menu');

function handel_add_endpoint() {
  add_rewrite_endpoint('assinaturas', EP_PAGES);
  // Se quiser adicionar certificados, descomente:
  // add_rewrite_endpoint('certificados', EP_PAGES);
}
add_action('init', 'handel_add_endpoint');

// Corrigindo para o endpoint correto (assinaturas)
function handel_assinaturas_content() {
  if(!SwpmMemberUtils::is_member_logged_in()) {
    return 0;
  }

  $member_level = SwpmMemberUtils::get_logged_in_members_level();

  if ($member_level == '3'){
    echo '<p>Assine a Filme b</p>';
  } else if ($member_level == '2') {
 
    $user_id = SwpmMemberUtils::get_logged_in_members_id();

    if ($user_id) {
        // Obtém as informações do usuário
        $user_info = SwpmMemberUtils::get_user_by_id($user_id);
        
        echo '<pre>';
        print_r($user_info);
        echo '</pre>';
    } else {
        echo 'Usuário não está logado.';
    }
    echo '<p>Voce ja é assinante, aproveite os conteudos exclusivos</p>';
  }
}
add_action('woocommerce_account_assinaturas_endpoint', 'handel_assinaturas_content');
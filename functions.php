<?php

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

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

function custom_archive_filmes_title($title)
{
  if (is_post_type_archive('filmes')) {
    $title = 'Lista de Filmes - ' . get_bloginfo('name');
  }
  return $title;
}
add_filter('pre_get_document_title', 'custom_archive_filmes_title');




// Usuarios associados


// 1. Adicionar campos personalizados ao formulário de registro
add_action('woocommerce_register_form_start', 'add_custom_fields_to_registration');

function add_custom_fields_to_registration()
{
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

function validate_custom_fields($username, $email, $validation_errors)
{
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

function save_custom_fields($customer_id)
{
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

add_action('woocommerce_created_customer', 'save_custom_fields_and_register_swpm');

function save_custom_fields_and_register_swpm($customer_id) {
    // Salva todos os campos personalizados no WooCommerce
    $fields_to_save = array(
        'billing_first_name', 'billing_last_name', 'billing_categoria_profissional',
        'billing_cpf_cnpj', 'billing_phone', 'billing_cellphone', 'billing_address',
        'billing_complemento', 'billing_bairro', 'billing_city', 'billing_state',
        'billing_postcode'
    );
    
    foreach ($fields_to_save as $field) {
        if (isset($_POST[$field])) {
            update_user_meta($customer_id, $field, sanitize_text_field($_POST[$field]));
        }
    }
    
    // Atualiza nome e sobrenome principais
    if (isset($_POST['billing_first_name'])) {
        wp_update_user(array('ID' => $customer_id, 'first_name' => sanitize_text_field($_POST['billing_first_name'])));
    }
    if (isset($_POST['billing_last_name'])) {
        wp_update_user(array('ID' => $customer_id, 'last_name' => sanitize_text_field($_POST['billing_last_name'])));
    }
    
    // Registra o usuário no Simple WordPress Membership com nível Free (ID:3)
    register_user_in_swpm($customer_id);
}

/**
 * 3. Função para registrar usuário no Simple Membership com nível Free
 */
function register_user_in_swpm($customer_id) {
    // Verifica se o plugin Simple Membership está ativo
    if (!class_exists('SimpleWpMembership')) {
        return;
    }
    
    // Obtém os dados do usuário
    $user = get_userdata($customer_id);
    $email = $user->user_email;
    $username = $user->user_login;
    
    // Configurações de membro - nível Free (ID:3)
    $membership_level = 3; // Nível Free
    $account_status = 'active'; // Status da conta
    
    // Verifica se o usuário já existe no Simple Membership
    global $wpdb;
    $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "swpm_members_tbl WHERE user_name = %s OR email = %s", $username, $email);
    $existing = $wpdb->get_row($query);
    
    if (!$existing) {
        // Prepara os dados extras do Simple Membership
        $swpm_user_data = array(
            'user_name' => $username,
            'password' => '', // Não definimos senha (usará a do WooCommerce)
            'first_name' => get_user_meta($customer_id, 'billing_first_name', true),
            'last_name' => get_user_meta($customer_id, 'billing_last_name', true),
            'email' => $email,
            'membership_level' => $membership_level,
            'member_since' => current_time('mysql'),
            'account_state' => $account_status,
            'last_accessed' => current_time('mysql'),
            // Campos personalizados adicionais
            'phone' => get_user_meta($customer_id, 'billing_phone', true),
            'cellphone' => get_user_meta($customer_id, 'billing_cellphone', true),
            'cpf_cnpj' => get_user_meta($customer_id, 'billing_cpf_cnpj', true),
            'categoria_profissional' => get_user_meta($customer_id, 'billing_categoria_profissional', true),
            'address' => get_user_meta($customer_id, 'billing_address', true),
            'complemento' => get_user_meta($customer_id, 'billing_complemento', true),
            'bairro' => get_user_meta($customer_id, 'billing_bairro', true),
            'city' => get_user_meta($customer_id, 'billing_city', true),
            'state' => get_user_meta($customer_id, 'billing_state', true),
            'postcode' => get_user_meta($customer_id, 'billing_postcode', true)
        );
        
        // Insere no banco de dados do Simple Membership
        $wpdb->insert($wpdb->prefix . 'swpm_members_tbl', $swpm_user_data);
        
        // Associa o ID do usuário WordPress ao membro
        $member_id = $wpdb->insert_id;
        update_user_meta($customer_id, 'swpm_member_id', $member_id);
        
        // Atualiza também a tabela de relacionamento de níveis (se necessário)
        $wpdb->replace($wpdb->prefix . 'swpm_membership_meta_tbl', array(
            'user_id' => $member_id,
            'membership_level' => $membership_level,
            'meta_key' => 'membership_level',
            'meta_value' => $membership_level
        ));
    }
}



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

function handel_custom_menu($menu_links)
{
  $menu_links = array_slice($menu_links, 0, 5, true)
    + ['assinaturas' => 'Assinaturas']
    + array_slice($menu_links, 3, NULL, true);

  unset($menu_links['downloads']);
  return $menu_links;
}
add_filter('woocommerce_account_menu_items', 'handel_custom_menu');

function handel_add_endpoint()
{
  add_rewrite_endpoint('assinaturas', EP_PAGES);
  // Se quiser adicionar certificados, descomente:
  // add_rewrite_endpoint('certificados', EP_PAGES);
}
add_action('init', 'handel_add_endpoint');

// Corrigindo para o endpoint correto (assinaturas)
function handel_assinaturas_content()
{
  if (!SwpmMemberUtils::is_member_logged_in()) {
    return 0;
  }

  $member_level = SwpmMemberUtils::get_logged_in_members_level();

  if ($member_level == '3') {
    // Mensagem para não assinantes (convite para assinar)
    echo '
      <div class="filme-b-promo" style="background: #f8f8f8; border-left: 4px solid #ff6b00; padding: 20px; margin: 20px 0; border-radius: 4px;">
          <h3 style="color: #ff6b00; margin-top: 0;">🎬 ACESSO EXCLUSIVO FILME B</h3>
          <p>Você está no nível <strong>Grátis</strong>. Assine o <strong>Filme B Premium</strong> e tenha:</p>
          <ul style="padding-left: 20px;">
              <li>✅ Conteúdos VIP exclusivos</li>
              <li>✅ Bastidores e making-of</li>
              <li>✅ Acesso antecipado a lançamentos</li>
          </ul>
          <a href="/assine/" style="background: #ff6b00; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 10px; font-weight: bold;">QUERO ASSINAR AGORA</a>
          <p style="font-size: 0.9em; margin-top: 10px; color: #666;">Garanta seu acesso ilimitado!</p>
      </div>';
  } else if ($member_level == '2') {
    $user_id = SwpmMemberUtils::get_logged_in_members_id();
    if ($user_id) {
      $user_info = SwpmMemberUtils::get_user_by_id($user_id);

      // Calcula a data de expiração (assumindo 1 ano de duração)
      $subscription_start = $user_info->subscription_starts;
      $expiry_date = date('Y-m-d', strtotime($subscription_start . ' +1 year'));
      $current_date = date('Y-m-d');
      $days_remaining = floor((strtotime($expiry_date) - strtotime($current_date)) / (60 * 60 * 24));

      // Mensagem personalizada conforme o status
      echo '
          <div class="assinatura-info" style="background: #f0f8ff; border-left: 4px solid #0066cc; padding: 20px; margin: 20px 0; border-radius: 4px;">
              <h3 style="color: #0066cc; margin-top: 0;">📅 SUA ASSINATURA FILME B</h3>
              <p><strong>👋 Olá, ' . esc_html($user_info->first_name) . '!</strong></p>
              <p><strong>📅 Início:</strong> ' . date('d/m/Y', strtotime($subscription_start)) . '</p>';

      if ($days_remaining > 0) {
        echo '
              <p><strong>⏳ Expira em:</strong> ' . date('d/m/Y', strtotime($expiry_date)) . ' <span style="color: #0066cc;">(' . floor($days_remaining) . ' dias restantes)</span></p>
              <p style="font-size: 1.4rem; color: #666;">Sua assinatura está ativa. Aproveite todos os benefícios!</p>';
      } else {
        echo '
              <p><strong>⚠️ Expirou em:</strong> ' . date('d/m/Y', strtotime($expiry_date)) . ' <span style="color: #cc0000;">(Assinatura encerrada)</span></p>
              <p style="font-size: 1.4rem; color: #cc0000;">Renove agora para continuar acessando o conteúdo exclusivo!</p>
              <a href="/renovar/" style="background: #cc0000; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 5px; font-weight: bold;">RENOVAR ASSINATURA</a>';
      }

      echo '
          </div>';
    }
  }
}
add_action('woocommerce_account_assinaturas_endpoint', 'handel_assinaturas_content');


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
  // Essa regex encontra o primeiro traço (–, —, -, −) e captura tudo depois dele
  $regex = '/[–—\-−][^a-zA-Z0-9]*([\p{L}].*)$/u';

    if (preg_match($regex, $texto, $matches)) {
        return trim($matches[1]);
    }

    return $texto;// Se não encontrar traço, retorna o texto original
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


function register_swpm_user_on_woocommerce_registration( $customer_id ) {
  // Verifica se o plugin Simple Membership está ativo
  if (!class_exists('SimpleWpMembership')) {
      return;
  }
  
  // Obtém os dados do usuário
  $user = get_userdata($customer_id);
  $email = $user->user_email;
  $username = $user->user_login;
  
  // Configurações básicas de membro (ajuste conforme necessário)
  $membership_level = 3; // Nível de membro padrão
  $account_status = 'active'; // Status da conta
  
  // Verifica se o usuário já existe no Simple Membership
  global $wpdb;
  $query = $wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "swpm_members_tbl WHERE user_name = %s OR email = %s", $username, $email);
  $existing = $wpdb->get_row($query);
  
  if (!$existing) {
      // Cria o membro no Simple Membership
      $swpm_user_data = array(
          'user_name' => $username,
          'password' => '', // Não definimos senha (usará a do WooCommerce)
          'first_name' => get_user_meta($customer_id, 'billing_first_name', true),
          'last_name' => get_user_meta($customer_id, 'billing_last_name', true),
          'email' => $email,
          'membership_level' => $membership_level,
          'member_since' => current_time('mysql'),
          'account_state' => $account_status,
          'last_accessed' => current_time('mysql'),
      );
      
      $wpdb->insert($wpdb->prefix . 'swpm_members_tbl', $swpm_user_data);
      
      // Associa o ID do usuário WordPress ao membro
      $member_id = $wpdb->insert_id;
      update_user_meta($customer_id, 'swpm_member_id', $member_id);
  }
}
add_action( 'woocommerce_created_customer', 'register_swpm_user_on_woocommerce_registration', 10, 1 );


/**
 * SINCRONIZAÇÃO ENTRE WOOCOMMERCE E SWPM
 * Quando usuário faz login no WooCommerce, também é autenticado no SWPM
 */
function sync_woocommerce_to_swpm_login($user_login, $user) {
  // Verifica se o plugin SWPM está ativo
  if (!class_exists('SimpleWpMembership')) {
      return;
  }

  // Se já estiver logado no SWPM, não faz nada
  if (SwpmMemberUtils::is_member_logged_in()) {
      return;
  }

  // Obtém o ID do membro SWPM pelo email do usuário
  $member_id = SwpmMemberUtils::get_user_by_email($user->user_email);
  
  if ($member_id) {
      // Verifica se é nível 3 (Free)
      $member_level = SwpmMemberUtils::get_membership_level_by_member_id($member_id);
      
      if ($member_level == 3) {
          // Autentica no SWPM
          $auth = SwpmAuth::get_instance();
          $auth->login($member_id);
          
          // Atualiza o último acesso
          SwpmMemberUtils::update_last_accessed_date($member_id);
      }
  }
}
add_action('wp_login', 'sync_woocommerce_to_swpm_login', 20, 2);

/**
* REDIRECIONAMENTO APÓS LOGIN
* Evita conflitos entre os sistemas
*/
function custom_login_redirect($redirect, $user) {
  if (class_exists('SimpleWpMembership') && SwpmMemberUtils::is_member_logged_in()) {
      return home_url('/minha-conta/'); // Página da conta WooCommerce
  }
  return $redirect;
}
add_filter('woocommerce_login_redirect', 'custom_login_redirect', 10, 2);

/**
* VERIFICA ACESSO ÀS PÁGINAS RESTRITAS
* Permite acesso se estiver logado no WooCommerce E for nível 3 no SWPM
*/
function check_swpm_access_for_woocommerce_users() {
  // Se não for página restrita, não faz nada
  if (!SwpmProtection::get_instance()->is_protected()) {
      return;
  }

  // Se já estiver logado no SWPM, não faz nada
  if (SwpmMemberUtils::is_member_logged_in()) {
      return;
  }

  // Se estiver logado no WooCommerce
  if (is_user_logged_in()) {
      $current_user = wp_get_current_user();
      $member_id = SwpmMemberUtils::get_user_by_email($current_user->user_email);
      
      if ($member_id) {
          $member_level = SwpmMemberUtils::get_membership_level_by_member_id($member_id);
          
          // Se for nível 3, autentica no SWPM
          if ($member_level == 3) {
              $auth = SwpmAuth::get_instance();
              $auth->login($member_id);
          }
      }
  }
}
add_action('wp', 'check_swpm_access_for_woocommerce_users', 99);

/**
 * 1. Verifica se o SWPM está ativo antes de qualquer operação
 */
function is_swpm_active() {
  return class_exists('SimpleWpMembership');
}

/**
* 2. Sincroniza login WooCommerce → SWPM (Nível 3)
*/
add_action('wp_login', function($user_login, $user) {
  if (!is_swpm_active()) return;

  // Evita duplo login no SWPM
  if (SwpmMemberUtils::is_member_logged_in()) return;

  $member_id = SwpmMemberUtils::get_user_by_email($user->user_email);
  
  if ($member_id) {
      $member_level = SwpmMemberUtils::get_membership_level_by_member_id($member_id);
      
      // Apenas para membros nível 3 (Free)
      if ($member_level == 3) {
          $auth = SwpmAuth::get_instance();
          $auth->login($member_id);
      }
  }
}, 20, 2);

/**
* 3. Força verificação de acesso nas páginas restritas
*/
add_action('template_redirect', function() {
  if (!is_swpm_active() || !SwpmProtection::get_instance()->is_protected()) return;
  
  // Se já está logado no SWPM, permite acesso
  if (SwpmMemberUtils::is_member_logged_in()) return;
  
  // Se está logado no WooCommerce, tenta autenticar no SWPM
  if (is_user_logged_in()) {
      $current_user = wp_get_current_user();
      $member_id = SwpmMemberUtils::get_user_by_email($current_user->user_email);
      
      if ($member_id && SwpmMemberUtils::get_membership_level_by_member_id($member_id) == 3) {
          $auth = SwpmAuth::get_instance();
          $auth->login($member_id);
          return; // Evita redirecionamento loop
      }
  }
  
  // Redireciona para login se não tiver acesso
  auth_redirect();
}, 99);
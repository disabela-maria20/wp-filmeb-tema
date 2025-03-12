<?php
add_action('rest_api_init', function () {
  register_rest_route('api/v1', '/add-user', [
    'methods' => 'POST',
    'callback' => 'register_customer_membership',
    'permission_callback' => '__return_true'
  ]);
});

function register_customer_membership(WP_REST_Request $request)
{
  global $wpdb;

  $params = $request->get_json_params();

  // Campos obrigatórios
  $username = sanitize_text_field($params['username']);
  $email = sanitize_email($params['email']);
  $password = sanitize_text_field($params['password']);
  $activation_date = sanitize_text_field($params['activation_date']);
  $expiration_date = sanitize_text_field($params['expiration_date']);

  // Verificação dos campos obrigatórios
  if (empty($username) || empty($email) || empty($password) || empty($expiration_date)) {
    return new WP_Error('missing_data', 'Os campos username, email, password e expiration_date são obrigatórios.', ['status' => 400]);
  }

  // Verifica se o usuário já existe
  if (email_exists($email)) {
    return new WP_Error('user_exists', 'Este email já está cadastrado.', ['status' => 400]);
  }

  // Criar usuário no WordPress e WooCommerce
  $user_id = wp_create_user($username, $password, $email);
  if (is_wp_error($user_id)) {
    return new WP_Error('user_creation_failed', 'Erro ao criar usuário.', ['status' => 500]);
  }

  $user = new WP_User($user_id);
  $user->set_role('customer'); // Define como Cliente no WooCommerce

  // Adicionar informações ao WooCommerce (campos obrigatórios)
  update_user_meta($user_id, 'billing_email', $email);
  update_user_meta($user_id, 'membership_activation', $activation_date);
  update_user_meta($user_id, 'membership_expiration', $expiration_date);

  // Verificar se a data de expiração já passou
  $current_date = current_time('mysql');
  $is_paid = strtotime($expiration_date) > strtotime($current_date);
  
  // Se ainda não expirou, é pago; caso contrário, é Free
  $membership_level = $is_paid ? 2 : 4;
  $account_state = $is_paid ? 'active' : 'inactive';
  $status = $is_paid ? 'paid' : 'free';

  // Inserir diretamente na tabela wp_swpm_members_tbl
  $table = $wpdb->prefix . 'swpm_members_tbl';

  // Dados para inserção na tabela SWPM
  $swpm_data = [
    'user_name' => $username,
    'first_name' => sanitize_text_field($params['first_name'] ?? ''),
    'last_name' => sanitize_text_field($params['last_name'] ?? ''),
    'password' => $password,
    'member_since' => $activation_date,
    'membership_level' => $membership_level,
    'account_state' => $account_state,
    'email' => $email,
    'phone' => sanitize_text_field($params['phone'] ?? ''),
    'address_street' => sanitize_text_field($params['address_street'] ?? ''),
    'address_city' => sanitize_text_field($params['address_city'] ?? ''),
    'address_state' => sanitize_text_field($params['address_state'] ?? ''),
    'address_zipcode' => sanitize_text_field($params['address_zipcode'] ?? ''),
    'country' => sanitize_text_field($params['country'] ?? ''),
    'gender' => sanitize_text_field($params['gender'] ?? 'not specified'),
    'company_name' => sanitize_text_field($params['company_name'] ?? ''),
    'profile_image' => sanitize_text_field($params['profile_image'] ?? ''),
    'subscription_starts' => $activation_date,
    'last_accessed' => current_time('mysql'),
    'last_accessed_from_ip' => $_SERVER['REMOTE_ADDR'],
  ];

  // Inserir na tabela SWPM
  $wpdb->insert($table, $swpm_data);

  // Criar um pedido no WooCommerce (se os dados de endereço forem fornecidos)
  if (isset($params['address_street']) && isset($params['address_city']) && isset($params['address_state']) && isset($params['address_zipcode']) && isset($params['country'])) {
    $order = wc_create_order(['customer_id' => $user_id]);

    // Adicionar o produto com ID 77471 ao pedido
    $product_id = 77471; // ID do produto
    $product = wc_get_product($product_id);

    if ($product) {
      $order->add_product($product, 1); // Adiciona 1 unidade do produto ao pedido
    } else {
      return new WP_Error('product_not_found', 'Produto não encontrado.', ['status' => 404]);
    }

    // Adicionar endereço de cobrança ao pedido
    $billing_address = [
      'first_name' => sanitize_text_field($params['first_name'] ?? ''),
      'last_name'  => sanitize_text_field($params['last_name'] ?? ''),
      'email'      => $email,
      'phone'      => sanitize_text_field($params['phone'] ?? ''),
      'address_1'  => sanitize_text_field($params['address_street']),
      'city'       => sanitize_text_field($params['address_city']),
      'state'      => sanitize_text_field($params['address_state']),
      'postcode'   => sanitize_text_field($params['address_zipcode']),
      'country'    => sanitize_text_field($params['country']),
    ];

    $order->set_address($billing_address, 'billing');

    // Definir status do pedido (opcional)
    $order->set_status('completed'); // Ou 'processing', 'pending', etc.
    $order->save();
  }

  return rest_ensure_response([
    'message' => 'Usuário registrado com sucesso!',
    'user_id' => $user_id,
    'status' => $status,
    'expiration_date' => $expiration_date
  ]);
}
?>
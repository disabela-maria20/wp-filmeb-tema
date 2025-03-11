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

  $username = sanitize_text_field($params['username']);
  $email = sanitize_email($params['email']);
  $password = sanitize_text_field($params['password']);
  $activation_date = sanitize_text_field($params['activation_date']);
  $expiration_date = sanitize_text_field($params['expiration_date']); // Data de expiração

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

  // Adicionar informações ao WooCommerce
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

  // Adicionar ou atualizar no Simple WordPress Membership
  $table = $wpdb->prefix . 'swpm_members_tbl';
  $existing_member = $wpdb->get_row($wpdb->prepare("SELECT id FROM $table WHERE email = %s", $email));

  if ($existing_member) {
    $wpdb->update($table, [
      'membership_level' => $membership_level,
      'account_state' => $account_state,
      'subscription_starts' => $activation_date
    ], ['email' => $email]);
  } else {
    $wpdb->insert($table, [
      'user_name' => $username,
      'email' => $email,
      'membership_level' => $membership_level,
      'account_state' => $account_state,
      'subscription_starts' => $activation_date
    ]);
  }

  return rest_ensure_response([
    'message' => 'Usuário registrado com sucesso!',
    'user_id' => $user_id,
    'status' => $status,
    'expiration_date' => $expiration_date
  ]);
}
?>
<!-- 

-------------------------------------- TUDO: -----------------------------------------
Filme B

Vincular os clientes com o woocommerce com a api,

os dados + data de encerramento + cliente do woocommerce como ja pago e concluído 

nome, email, password, data de ativação, data de encerramento, status do cliente (pago e concluído)
e Vincular esse cadastro a Membership os clintes que tem conta ativa, ou seja a data de expiração não chegou tem que esta como Cliente e vai ter acesso a
conteudo exclusivos se a data ja passou e não esta como pago vai para o plano free 

em seguida no wordpress vincllar o cadastro dos clintes woocommerce com Simple WordPress Membership

o usuarioi vai se cadastrar com o woocomerce então apartir dele veja o nivel de associação, para os usuarios que ja existe eplique a logica de clinte ou free

-->
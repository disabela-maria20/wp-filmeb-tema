<?php
add_action('rest_api_init', function () {
  register_rest_route('api/v1', '/add-user', [
    'methods' => 'POST',
    'callback' => 'custom_add_user',
    'permission_callback' => '__return_true'
  ]);

  register_rest_route('api/v1', '/list-users', [
    'methods' => 'GET',
    'callback' => 'custom_list_users',
    'permission_callback' => '__return_true'
  ]);
});

function custom_add_user(WP_REST_Request $request)
{
  $params = $request->get_json_params();

  $username = sanitize_text_field($params['username']);
  $email = sanitize_email($params['email']);
  $password = sanitize_text_field($params['password']);
  $role = !empty($params['role']) ? sanitize_text_field($params['role']) : 'subscriber';

  if (empty($username) || empty($email) || empty($password)) {
    return new WP_Error('missing_data', 'Nome de usuário, email e senha são obrigatórios.', ['status' => 400]);
  }

  $user_id = wp_create_user($username, $password, $email);
  if (is_wp_error($user_id)) {
    return new WP_Error('user_creation_failed', 'Erro ao criar usuário.', ['status' => 500]);
  }

  $user = new WP_User($user_id);
  $user->set_role($role);

  global $wpdb;
  $table = $wpdb->prefix . 'swpm_members_tbl';
  $activation_date = current_time('mysql');

  $wpdb->insert($table, [
    'user_name' => $username,
    'email' => $email,
    'membership_level' => 2,
    'account_state' => 'active',
    'subscription_starts' => $activation_date
  ]);

  return rest_ensure_response(['message' => 'Usuário criado com sucesso!', 'user_id' => $user_id]);
}

function custom_list_users(WP_REST_Request $request)
{
  global $wpdb;
  $table = $wpdb->prefix . 'swpm_members_tbl';

  $results = $wpdb->get_results("SELECT id, user_name, email, account_state, subscription_starts FROM $table");

  $users = [
    'ativos' => [],
    'inativos' => []
  ];

  foreach ($results as $user) {
    $data = [
      'id' => $user->id,
      'username' => $user->user_name,
      'email' => $user->email,
      'data_ativacao' => $user->subscription_starts
    ];

    if ($user->account_state === 'active') {
      $users['ativos'][] = $data;
    } else {
      $users['inativos'][] = $data;
    }
  }

  return rest_ensure_response($users);
}
?>
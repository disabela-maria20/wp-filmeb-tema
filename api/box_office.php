<?php
add_action('rest_api_init', function () {
    register_rest_route('boxoffice/v1', '/submit', array(
        'methods' => 'POST',
        'callback' => 'box_office_process_form',
        'permission_callback' => '__return_true'
    ));
});

function box_office_process_form(WP_REST_Request $request) {
    // Verificar nonce
    if (!wp_verify_nonce($request->get_param('security'), 'box_office_lead_nonce')) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'Token de segurança inválido'
        ], 403);
    }

    // Sanitizar dados
    $data = [
        'nome' => sanitize_text_field($request->get_param('nome')),
        'sobreNome' => sanitize_text_field($request->get_param('sobreNome')),
        'email' => sanitize_email($request->get_param('email')),
        'telefone' => sanitize_text_field($request->get_param('telefone')),
        'produto_interesse' => sanitize_text_field($request->get_param('produto_interesse')),
        'mensagem' => sanitize_textarea_field($request->get_param('mensagem'))
    ];

    // Validações
    $errors = [];

    // Nome e Sobrenome
    $name_regex = '/^[a-zA-ZÀ-ú\s]{2,}$/';
    if (empty($data['nome']) || !preg_match($name_regex, $data['nome'])) {
        $errors['nome'] = 'Nome é obrigatório (mínimo 2 letras)';
    }
    if (empty($data['sobreNome']) || !preg_match($name_regex, $data['sobreNome'])) {
        $errors['sobreNome'] = 'Sobrenome é obrigatório (mínimo 2 letras)';
    }

    // E-mail
    if (empty($data['email']) || !is_email($data['email'])) {
        $errors['email'] = 'E-mail válido é obrigatório';
    }

    // Telefone (11 dígitos)
    $telefone_digits = preg_replace('/\D/', '', $data['telefone']);
    if (empty($telefone_digits) || strlen($telefone_digits) !== 11) {
        $errors['telefone'] = 'Telefone deve ter 11 dígitos';
    } else {
        $data['telefone'] = '(' . substr($telefone_digits, 0, 2) . ') ' 
                          . substr($telefone_digits, 2, 5) . '-' 
                          . substr($telefone_digits, 7, 4);
    }

    // Produto
    $produtos_validos = ['Banco de Dados', 'Filme B Report', 'Filme B Ontime', 'Sadis ANCINE'];
    if (empty($data['produto_interesse']) || !in_array($data['produto_interesse'], $produtos_validos)) {
        $errors['produto_interesse'] = 'Selecione um produto válido';
    }

    // Mensagem
    if (empty($data['mensagem']) || strlen(trim($data['mensagem'])) < 10) {
        $errors['mensagem'] = 'Mensagem deve ter pelo menos 10 caracteres';
    }

    // Retornar erros se houver
    if (!empty($errors)) {
        return new WP_REST_Response([
            'success' => false,
            'message' => 'Corrija os campos destacados',
            'errors' => $errors
        ], 400);
    }

    // Enviar e-mail
    $to = get_option('admin_email');
    $subject = "Novo contato: {$data['nome']} - Interesse em {$data['produto_interesse']}";
    
    $message = "
        <h2>Novo Lead Recebido</h2>
        <p><strong>Nome completo:</strong> {$data['nome']} {$data['sobreNome']}</p>
        <p><strong>Contato:</strong> {$data['email']} | {$data['telefone']}</p>
        <p><strong>Produto de interesse:</strong> {$data['produto_interesse']}</p>
        <p><strong>Mensagem:</strong></p>
        <div style='background:#f8fafc;padding:15px;border-radius:6px;margin-top:10px;'>
            {$data['mensagem']}
        </div>
        <p style='margin-top:20px;color:#718096;font-size:14px;'>
            Enviado em: " . date('d/m/Y H:i:s') . "
        </p>
    ";
    
    $headers = [
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $data['nome'] . ' <' . $data['email'] . '>',
        'Reply-To: ' . $data['nome'] . ' <' . $data['email'] . '>'
    ];

    if (wp_mail($to, $subject, $message, $headers)) {
        return new WP_REST_Response([
            'success' => true,
            'message' => 'Obrigado pelo seu contato! Retornaremos em breve.'
        ]);
    }

    return new WP_REST_Response([
        'success' => false,
        'message' => 'Erro ao enviar mensagem. Tente novamente mais tarde.'
    ], 500);
}
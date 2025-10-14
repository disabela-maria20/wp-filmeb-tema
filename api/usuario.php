<?php
add_action( 'rest_api_init', function() {
    register_rest_route( 'api/v1', '/add-user', [
        'methods'             => 'POST',
        'callback'            => 'am_add_user_membership',
        'permission_callback' => '__return_true',
    ] );
} );

/**
 * Callback do endpoint
 */
function am_add_user_membership( WP_REST_Request $request ) {
    error_log( '--- am_add_user_membership chamado ---' );
    $params = $request->get_json_params();
    error_log( 'Payload recebido: ' . print_r( $params, true ) );

    // Validação de e-mail
    if ( empty( $params['email'] ) || ! is_email( $params['email'] ) ) {
        return new WP_Error( 'invalid_email', 'E-mail inválido ou ausente.', [ 'status' => 400 ] );
    }

    $email      = sanitize_email( $params['email'] );
    $first_name = isset( $params['first_name'] ) ? sanitize_text_field( $params['first_name'] ) : '';
    $last_name  = isset( $params['last_name'] )  ? sanitize_text_field( $params['last_name'] )  : '';

    // --- Validação e conversão das datas ---
    $start_raw = trim( $params['start_date'] ?? '' );

    if ( empty( $start_raw ) ) {
        return new WP_Error( 'invalid_start_date', 'start_date ausente.', [ 'status' => 400 ] );
    }

    // Aceita formatos: YYYY-MM-DD, YYYY/MM/DD, ou ISO (YYYY-MM-DDTHH:MM:SS)
    $start_raw = str_replace( ['/', 'T'], ['-', ' '], $start_raw );

    $ts_start = strtotime( $start_raw );
    if ( ! $ts_start ) {
        return new WP_Error(
            'invalid_start_date',
            "start_date inválido ({$start_raw}). Use formato YYYY-MM-DD.",
            [ 'status' => 400 ]
        );
    }

    $start_date = date( 'Y-m-d H:i:s', $ts_start );

    $end_date = '';
    if ( ! empty( $params['end_date'] ) ) {
        $end_raw = str_replace( ['/', 'T'], ['-', ' '], trim( $params['end_date'] ) );
        $ts_end = strtotime( $end_raw );
        if ( ! $ts_end ) {
            return new WP_Error(
                'invalid_end_date',
                "end_date inválido ({$end_raw}). Use formato YYYY-MM-DD.",
                [ 'status' => 400 ]
            );
        }
        $end_date = date( 'Y-m-d H:i:s', $ts_end );
    }

    // Criação ou recuperação do usuário
    $user = get_user_by( 'email', $email );
    if ( ! $user ) {
        $random_pass = wp_generate_password();
        $user_id = wp_create_user( $email, $random_pass, $email );
        if ( is_wp_error( $user_id ) ) {
            error_log( 'Erro ao criar usuário: ' . $user_id->get_error_message() );
            return new WP_Error( 'user_creation_failed', 'Não foi possível criar usuário.', [ 'status' => 500 ] );
        }

        $user = get_user_by( 'ID', $user_id );
        wp_update_user([
            'ID'         => $user_id,
            'first_name' => $first_name,
            'last_name'  => $last_name,
        ]);

        wp_mail(
            $email,
            'Bem-vindo(a)!',
            "Olá {$first_name},\n\nSua conta foi criada com sucesso. Sua senha é: {$random_pass}"
        );

        error_log( "Usuário {$email} criado com ID {$user_id}" );
    }

    $user_id = $user->ID;

    // Parâmetros da assinatura
    $plan_id    = 162; // ajuste conforme necessário
    $product_id = 152; // ajuste conforme necessário

    $args = [
        'plan_id'    => $plan_id,
        'user_id'    => $user_id,
        'product_id' => $product_id,
        'start_date' => $start_date,
        'status'     => 'active',
    ];

    if ( $end_date ) {
        $args['end_date'] = $end_date;
    }

    error_log( 'Chamando wc_memberships_create_user_membership com: ' . print_r( $args, true ) );

    // Criação da assinatura
    try {
        $membership = wc_memberships_create_user_membership( $args );
        if ( is_wp_error( $membership ) ) {
            throw new Exception( $membership->get_error_message() );
        }

        $membership->set_start_date( $start_date );
        if ( $end_date ) {
            $membership->set_end_date( $end_date );
        }

        $membership_id = $membership->get_id();
        error_log( "Membership criado com ID {$membership_id}" );

    } catch ( Throwable $e ) {
        error_log( 'Exceção ao criar membership: ' . $e->getMessage() );
        return new WP_Error(
            'membership_error',
            'Erro ao criar membership: ' . $e->getMessage(),
            [ 'status' => 500 ]
        );
    }

    // Resposta final
    return rest_ensure_response([
        'success'        => true,
        'message'        => 'Assinante adicionado com sucesso.',
        'membership_id'  => $membership_id,
        'user_id'        => $user_id,
        'email'          => $email,
        'start_date'     => $start_date,
        'end_date'       => $end_date ?: null,
    ]);
}
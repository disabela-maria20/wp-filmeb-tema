<?php

function registrar_cpt_rapidinhas()
{
    register_post_type('rapidinhas', array(
        'labels' => array(
            'name' => _x('Rapidinhas', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Rapidinha', 'Post type singular name', 'textdomain'),
            'add_new_item' => _x('Adicionar Nova Rapidinha', 'Post type singular name', 'textdomain'),
            'new_item' => _x('Nova Rapidinha', 'Post type singular name', 'textdomain'),
            'edit_item' => _x('Editar Rapidinha', 'Post type singular name', 'textdomain'),
            'view_item' => _x('Ver Rapidinha', 'Post type singular name', 'textdomain'),
        ),
        'description' => 'Gerenciar Rapidinhas',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'rapidinhas', 'with_front' => true),
        'query_var' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'publicly_queryable' => true,
        'has_archive' => true,
        'show_in_rest' => true,
        'taxonomies' => array('category'),
    ));
}
add_action('init', 'registrar_cpt_rapidinhas');


function registrar_cpt_edicoes()
{
    register_post_type('edicoes', array(
        'labels' => array(
            'name' => _x('Edições', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Edição', 'Post type singular name', 'textdomain'),
            'add_new_item' => _x('Adicionar Edição', 'Post type singular name', 'textdomain'),
            'new_item' => _x('Nova Edição', 'Post type singular name', 'textdomain'),
            'edit_item' => _x('Editar Edição', 'Post type singular name', 'textdomain'),
            'view_item' => _x('Ver Edição', 'Post type singular name', 'textdomain'),
        ),
        'description' => 'Gerenciar Edições',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'edicao', 'with_front' => true),
        'query_var' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'publicly_queryable' => true,
        'has_archive' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-book-alt',
        'show_in_rest' => true,
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    ));
}
add_action('init', 'registrar_cpt_edicoes');


function api_rapidinhas_post($request) {
    $data = $request->get_json_params();

    $titulo = isset($data['titulo']) ? sanitize_text_field($data['titulo']) : '';
    $descricao = isset($data['descricao']) ? sanitize_textarea_field($data['descricao']) : '';
    $imagem_url = isset($data['imagem']) ? esc_url_raw($data['imagem']) : '';
    $edicao_id = isset($data['edicao']) ? sanitize_text_field($data['edicao']) : ''; // Pode ser string ou número
    $data_rapidinha = isset($data['data']) ? sanitize_text_field($data['data']) : ''; // Campo data

    // Verifica se a imagem foi fornecida e tenta fazer o upload
    $attachment_id = '';
    $imagem_salva_url = false;
    if (!empty($imagem_url)) {
        $attachment_id = upload_image_from_url($imagem_url);
        if (is_wp_error($attachment_id)) {
            return rest_ensure_response([
                'message' => 'Erro ao fazer upload da imagem.',
                'success' => false,
                'error' => $attachment_id->get_error_message()
            ]);
        }
        $imagem_salva_url = wp_get_attachment_url($attachment_id);
    }

    // Cria o post
    $post_data = [
        'post_type' => 'rapidinhas',
        'post_title' => $titulo,
        'post_status' => 'publish',
        'post_content' => $descricao
    ];
    
    $post_id = wp_insert_post($post_data);
    if (is_wp_error($post_id)) {
        return rest_ensure_response([
            'message' => 'Erro ao criar o post.',
            'success' => false,
            'post_id' => $post_id
        ]);
    }

    // Salva os campos personalizados
    $field_data = [
        'imagem' => $imagem_salva_url,
        'edicao' => $edicao_id, // Agora pode ser uma string ou número
        'data' => $data_rapidinha 
    ];
    CFS()->save($field_data, ['ID' => $post_id]);

    // Retorna a resposta de sucesso
    return rest_ensure_response([
        'message' => 'Rapidinha criada com sucesso!',
        'success' => true,
        'post_id' => $post_id,
        'image_url' => $imagem_salva_url,
        'edicao' => $edicao_id, // Retorna o valor original (string ou número)
        'data' => $data_rapidinha
    ]);
}
function api_edicoes_post($request)
{
    $data = $request->get_json_params();
    $titulo = isset($data['titulo']) ? sanitize_text_field($data['titulo']) : '';
    $data_lancamento = isset($data['data']) ? sanitize_text_field($data['data']) : '';


    $edicao = isset($data['edicao']) ? (array) $data['edicao'] : [];
    if (empty($edicao)) {
        return new WP_Error('no_edicao_data', 'Nenhuma edição fornecida.', ['status' => 400]);
    }

    $edicao_ids = array_map('intval', $edicao);
    if (empty($edicao_ids) || in_array(0, $edicao_ids, true)) {
        return new WP_Error('invalid_edicao_ids', 'IDs de edição inválidos fornecidos.', ['status' => 400]);
    }

    global $wpdb;
    $placeholders = implode(',', array_fill(0, count($edicao_ids), '%d'));
    $query = $wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'rapidinhas' AND post_status = 'publish' AND ID IN ($placeholders)",
        ...$edicao_ids
    );
    $rapidinhas = $wpdb->get_col($query);

    if (empty($rapidinhas)) {
        return new WP_Error('no_rapidinhas_found', 'Nenhuma rapidinha encontrada para os IDs fornecidos.', ['status' => 400]);
    }


    $post_data = [
        'post_type' => 'edicoes',
        'post_title' => $titulo,
        'post_status' => 'publish',
    ];

    $post_id = wp_insert_post($post_data);

    if ($post_id) {

        $edicao_ids_terms = obter_term_ids($edicao_ids, 'edicao');
        wp_set_object_terms($post_id, $edicao_ids_terms, 'edicao');

        update_post_meta($post_id, 'rapidinhas_relacionadas', $rapidinhas);

        $field_data = [
            'edicao' => $rapidinhas,
            'data' => $data_lancamento
        ];
        CFS()->save($field_data, ['ID' => $post_id]);

        return rest_ensure_response([
            'message' => 'Edição criada com sucesso!',
            'post_id' => $post_id,
            'data' => $field_data
        ]);
    }

    return new WP_Error('filme_nao_criado', 'Erro ao criar a edição', ['status' => 500]);
}


function register_api_rapidinhas_post()
{
    register_rest_route('api/v1', '/rapidinhas', [
        'methods' => 'POST',
        'callback' => 'api_rapidinhas_post',
    ]);

    register_rest_route('api/v1', '/edicoes', [
        'methods' => 'POST',
        'callback' => 'api_edicoes_post',
    ]);
}

add_action('rest_api_init', 'register_api_rapidinhas_post');
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
    ));
}
add_action('init', 'registrar_cpt_edicoes');


function api_rapidinhas_post($request)
{
    $data = $request->get_json_params();

    $titulo = isset($data['titulo']) ? sanitize_text_field($data['titulo']) : '';
    $descricao = isset($data['descricao']) ? sanitize_textarea_field($data['descricao']) : '';
    $imagem_url = isset($data['imagem']) ? esc_url_raw($data['imagem']) : '';

    if (empty($imagem_url)) {
        return new WP_Error('no_image_url', 'Nenhuma URL de imagem fornecida.', ['status' => 400]);
    }

    // Faz o download da imagem externa e adiciona à biblioteca de mídia
    $attachment_id = upload_image_from_url($imagem_url);
    if (is_wp_error($attachment_id)) {
        return new WP_Error('upload_error', 'Erro ao baixar a imagem.', ['status' => 500]);
    }

    // Obtém a URL da imagem salva
    $imagem_salva_url = wp_get_attachment_url($attachment_id);

    // Cria o post no WordPress
    $post_data = [
        'post_type'    => 'rapidinhas',
        'post_title'   => $titulo,
        'post_status'  => 'publish',
        'post_content' => $descricao
    ];
    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Associa a imagem como thumbnail do post (Opcional)
        set_post_thumbnail($post_id, $attachment_id);

        // Salva a URL da imagem como campo personalizado via CFS
        $field_data = ['imagem' => $imagem_salva_url];
        CFS()->save($field_data, ['ID' => $post_id]);

        return rest_ensure_response([
            'message'   => 'Filme criado com sucesso!',
            'post_id'   => $post_id,
            'image_url' => $imagem_salva_url
        ]);
    }

    return new WP_Error('filme_nao_criado', 'Erro ao criar o filme', ['status' => 500]);
}

function register_api_rapidinhas_post()
{
    register_rest_route('api/v1', '/rapidinhas', [
        'methods'             => 'POST',
        'callback'            => 'api_rapidinhas_post',
    ]);
}

add_action('rest_api_init', 'register_api_rapidinhas_post');

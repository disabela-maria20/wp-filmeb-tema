<?php
function api_noticia_post($request) {
    $data = $request->get_json_params();

    // Sanitização dos campos básicos
    $titulo = !empty($data['titulo']) ? sanitize_text_field($data['titulo']) : '';
    $descricao = !empty($data['descricao']) ? sanitize_textarea_field($data['descricao']) : '';
    $post_excerpt = !empty($data['post_excerpt']) ? sanitize_text_field($data['post_excerpt']) : '';
    $post_author = !empty($data['post_author']) ? intval($data['post_author']) : get_current_user_id();
    $cartaz = !empty($data['cartaz']) ? esc_url_raw($data['cartaz']) : '';
    $data_filme = !empty($data['data_filme']) ? sanitize_text_field($data['data_filme']) : '';
    $chapel = !empty($data['chapel']) ? sanitize_text_field($data['chapel']) : '';
    $edicao = !empty($data['edicao']) ? sanitize_text_field($data['edicao']) : '';

    // Processamento das categorias (agora aceita array)
    $category_ids = [];
    if (!empty($data['post_category'])) {
        // Garante que temos um array (transforma string única em array com um elemento)
        $categories = is_array($data['post_category']) ? $data['post_category'] : [$data['post_category']];
        
        foreach ($categories as $category_name) {
            $category_name = sanitize_text_field($category_name);
            if (empty($category_name)) continue;

            // Verifica se a categoria já existe
            $category = get_term_by('name', $category_name, 'category');

            if (!$category) {
                // Cria a categoria se não existir
                $new_category = wp_insert_term($category_name, 'category');
                
                if (!is_wp_error($new_category)) {
                    $category_ids[] = $new_category['term_id'];
                }
            } else {
                $category_ids[] = $category->term_id;
            }
        }
    }

    // Upload da imagem do cartaz (se existir)
    $cartaz_id = '';
    if (!empty($cartaz)) {
        $cartaz_id = upload_image_from_url($cartaz);
        if (is_wp_error($cartaz_id)) {
            return new WP_Error(
                'upload_error', 
                'Erro ao baixar a imagem do cartaz.', 
                ['status' => 500, 'data' => $cartaz]
            );
        }
    }

    // Dados para criação do post
    $post_data = [
        'post_type' => 'post',
        'post_title' => $titulo,
        'post_status' => 'publish',
        'post_content' => $descricao,
        'post_excerpt' => $post_excerpt,
        'post_category' => $category_ids,
        'post_author' => $post_author,
        'meta_input' => [
            'data_filme' => $data_filme,
        ],
    ];

    // Criação do post
    $post_id = wp_insert_post($post_data);

    if ($post_id) {
        // Configura a imagem destacada se existir
        if (!empty($cartaz_id)) {
            set_post_thumbnail($post_id, $cartaz_id);
            
            // Salva a URL da imagem nos campos personalizados (CFS)
            $field_data = ['imagem' => wp_get_attachment_url($cartaz_id)];
            CFS()->save($field_data, ['ID' => $post_id]);
        }

        // Salva os campos personalizados
        $custom_fields = [
            'chapeu' => $chapel,
            'edicao' => $edicao,
            'data' => $data_filme
        ];
        CFS()->save($custom_fields, ['ID' => $post_id]);

        // Resposta de sucesso
        return rest_ensure_response([
            'message' => 'Notícia criada com sucesso!',
            'post_id' => $post_id,
            'category_ids' => $category_ids,
            'data_filme' => $data_filme,
            'chapel' => $chapel,
            'edicao' => $edicao,
            'cartaz_url' => !empty($cartaz_id) ? wp_get_attachment_url($cartaz_id) : null,
        ]);
    }

    // Resposta de erro se o post não for criado
    return new WP_Error(
        'Noticia_nao_criada', 
        'Erro ao criar a notícia', 
        ['status' => 500]
    );
}

function register_noticias_api_endpoints() {
    register_rest_route('api/v1', '/noticias', [
        'methods' => 'POST',
        'callback' => 'api_noticia_post',
        'permission_callback' => function() {
            return current_user_can('edit_posts'); // Apenas usuários que podem editar posts
        }
    ]);
}
add_action('rest_api_init', 'register_noticias_api_endpoints');

// Função auxiliar para upload de imagem por URL (caso não exista)
if (!function_exists('upload_image_from_url')) {
    function upload_image_from_url($image_url) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        
        $tmp = download_url($image_url);
        
        if (is_wp_error($tmp)) {
            return $tmp;
        }
        
        $file_array = [
            'name' => basename($image_url),
            'tmp_name' => $tmp
        ];
        
        $id = media_handle_sideload($file_array, 0);
        
        if (is_wp_error($id)) {
            @unlink($file_array['tmp_name']);
        }
        
        return $id;
    }
}
?>
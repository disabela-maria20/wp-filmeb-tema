<?php
/**
 * Registra as taxonomias customizadas para filmes
 */
add_action('init', 'registrar_taxonomias_filmes', 5);
function registrar_taxonomias_filmes() {
    $taxonomias = [
        'distribuidoras' => 'Distribuidoras',
        'paises' => 'Países',
        'generos' => 'Gêneros',
        'classificacoes' => 'Classificações',
        'tecnologias' => 'Tecnologias',
        'feriados' => 'Feriados',
    ];

    foreach ($taxonomias as $slug => $nome) {
        register_taxonomy(
            $slug,
            'filmes',
            [
                'labels' => [
                    'name' => $nome,
                    'singular_name' => $nome,
                    'search_items' => "Buscar $nome",
                    'all_items' => "Todos os $nome",
                    'edit_item' => "Editar $nome",
                    'update_item' => "Atualizar $nome",
                    'add_new_item' => "Adicionar Novo $nome",
                    'new_item_name' => "Novo $nome",
                    'menu_name' => $nome,
                ],
                'hierarchical' => true,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_rest' => true,
                'rewrite' => ['slug' => $slug],
                'rest_controller_class' => 'WP_REST_Terms_Controller',
            ]
        );
    }
}

/**
 * Registra o CPT Filmes
 */
add_action('init', 'registrar_cpt_filmes', 10);
function registrar_cpt_filmes() {
    register_post_type('filmes', [
        'labels' => [
            'name' => _x('Filmes', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Filme', 'Post type singular name', 'textdomain'),
            'add_new_item' => __('Adicionar Novo Filme', 'textdomain'),
            'edit_item' => __('Editar Filme', 'textdomain'),
            'new_item' => __('Novo Filme', 'textdomain'),
            'view_item' => __('Ver Filme', 'textdomain'),
            'search_items' => __('Buscar Filmes', 'textdomain'),
            'not_found' => __('Nenhum Filme encontrado', 'textdomain'),
            'not_found_in_trash' => __('Nenhum Filme encontrado na lixeira', 'textdomain'),
        ],
        'description' => 'Gerenciar Filmes',
        'public' => true,
        'show_ui' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-video-alt',
        'capability_type' => 'post',
        'rewrite' => ['slug' => 'filmes', 'with_front' => true],
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive' => true,
        'publicly_queryable' => true,
        'show_in_rest' => true,
        'taxonomies' => ['distribuidoras', 'paises', 'generos', 'classificacoes', 'tecnologias', 'feriados', 'post_tag'],
    ]);
}

/**
 * Função auxiliar para obter IDs de termos
 */
function obter_term_ids($terms, $taxonomy) {
    $term_ids = [];
    
    if (!is_array($terms)) {
        return $term_ids;
    }

    foreach ($terms as $term_name) {
        if (!empty($term_name)) {
            // Verifica se o termo existe
            $term = term_exists($term_name, $taxonomy);
            
            // Se não existir, cria o termo
            if (!$term) {
                $term = wp_insert_term($term_name, $taxonomy);
            }
            
            // Se não houver erro e existir ID, adiciona ao array
            if (!is_wp_error($term) && isset($term['term_id'])) {
                $term_ids[] = $term['term_id'];
            }
        }
    }
    return $term_ids;
}

/**
 * Função auxiliar para upload de imagens a partir de URL
 */
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
    
    @unlink($tmp); // Limpa o arquivo temporário
    
    return $id;
}

/**
 * Schema para os dados do filme
 */
function filme_scheme($post) {
    $filme = new stdClass();
    $filme->title = get_the_title($post);
    $filme->link = get_permalink($post);
    $filme->descricao = get_the_content(null, false, $post);
    $filme->titulo_original = cfs()->get('titulo_original', $post->ID);
    $filme->trailer = esc_url(cfs()->get('trailer', $post->ID));
    $filme->cartaz = esc_url(cfs()->get('cartaz', $post->ID));
    $filme->capa = esc_url(cfs()->get('capa', $post->ID));

    // Processa fotos
    $fotos = cfs()->get('fotos', $post->ID);
    $filme->fotos = [];
    if ($fotos && is_array($fotos)) {
        foreach ($fotos as $foto) {
            if (!empty($foto['foto'])) {
                $filme->fotos[] = ['foto' => esc_url($foto['foto'])];
            }
        }
    }

    // Informações básicas
    $filme->direcao = sanitize_text_field(cfs()->get('direcao', $post->ID));
    $filme->roteiro = sanitize_text_field(cfs()->get('roteiro', $post->ID));
    $filme->elenco = sanitize_text_field(cfs()->get('elenco', $post->ID));
    $filme->estreia = sanitize_text_field(cfs()->get('estreia', $post->ID));
    $filme->duracao_minutos = intval(cfs()->get('duracao_minutos', $post->ID));

    // Data de estreia
    if (!empty($filme->estreia)) {
        $filme->ano = (int) date('Y', strtotime($filme->estreia));
        $filme->mes = date('F', strtotime($filme->estreia));
    } else {
        $filme->ano = null;
        $filme->mes = null;
    }

    // Processa taxonomias
    $filme->distribuidoras = processar_terms('distribuicao', 'distribuidoras', $post->ID);
    $filme->paises = processar_terms('paises', 'paises', $post->ID);
    $filme->generos = processar_terms('generos', 'generos', $post->ID);
    $filme->classificacoes = processar_terms('classificacao', 'classificacoes', $post->ID);
    $filme->tecnologias = processar_terms('tecnologias', 'tecnologias', $post->ID);
    $filme->feriados = processar_terms('feriados', 'feriados', $post->ID);

    return $filme;
}

/**
 * Função auxiliar para processar termos de taxonomias
 */
function processar_terms($field_name, $taxonomy, $post_id) {
    $terms = [];
    $term_ids = cfs()->get($field_name, $post_id);
    
    if (is_array($term_ids)) {
        foreach ($term_ids as $term_id) {
            $term = get_term($term_id, $taxonomy);
            if ($term && !is_wp_error($term)) {
                $terms[] = $term->name;
            }
        }
    }
    
    return $terms;
}

/**
 * Endpoint GET para filmes
 */
function api_filmes_get($request) {
    $q = isset($request['q']) ? sanitize_text_field($request['q']) : '';
    $ano = isset($request['ano']) ? sanitize_text_field($request['ano']) : '';

    $query = [
        'post_type' => 'filmes',
        's' => $q,
        'nopaging' => true,
    ];

    if (!empty($ano)) {
        $query['meta_query'] = [
            [
                'key' => 'estreia',
                'value' => $ano,
                'compare' => 'LIKE',
                'type' => 'CHAR',
            ]
        ];
    }

    $loop = new WP_Query($query);
    $filmes = [];

    foreach ($loop->posts as $post) {
        $filmes[] = filme_scheme($post);
    }

    return rest_ensure_response($filmes);
}

/**
 * Endpoint GET para anos de filmes
 */
function api_anos_filmes_get() {
    global $wpdb;
    
    $results = $wpdb->get_col(
        "SELECT DISTINCT YEAR(meta_value) 
         FROM $wpdb->postmeta 
         WHERE meta_key = 'estreia' 
         AND meta_value != '' 
         ORDER BY YEAR(meta_value) DESC"
    );

    return rest_ensure_response($results);
}

/**
 * Endpoint POST para criar filmes
 */
function api_filmes_post($request) {
    $data = $request->get_json_params();

    // Validação básica
    if (empty($data['titulo'])) {
        return new WP_Error('dados_invalidos', 'O título do filme é obrigatório', ['status' => 400]);
    }

    // Dados básicos
    $titulo = sanitize_text_field($data['titulo']);
    $descricao = sanitize_textarea_field($data['descricao'] ?? '');
    $titulo_original = sanitize_text_field($data['titulo_original'] ?? '');
    $trailer = esc_url_raw($data['trailer'] ?? '');
    $cartaz = esc_url_raw($data['cartaz'] ?? '');
    $capa = esc_url_raw($data['capa'] ?? '');
    $estreia = sanitize_text_field($data['estreia'] ?? '');
    $duracao_minutos = intval($data['duracao_minutos'] ?? 0);

    // Processa arrays
    $direcao = processar_pessoas($data['direcao'] ?? []);
    $roteiro = processar_pessoas($data['roteiro'] ?? []);
    $elenco = processar_pessoas($data['elenco'] ?? []);
    $fotos = processar_fotos($data['fotos'] ?? []);

    // Processa taxonomias
    $distribuicao_ids = obter_term_ids($data['distribuicao'] ?? [], 'distribuidoras');
    $paises_ids = obter_term_ids($data['paises'] ?? [], 'paises');
    $genero_ids = obter_term_ids($data['generos'] ?? [], 'generos');
    $classificacao_ids = obter_term_ids($data['classificacoes'] ?? [], 'classificacoes');
    $tecnologia_ids = obter_term_ids($data['tecnologias'] ?? [], 'tecnologias');
    $feriado_ids = obter_term_ids($data['feriados'] ?? [], 'feriados');

    // Upload de imagens
    $cartaz_id = !empty($cartaz) ? upload_image_from_url($cartaz) : null;
    $capa_id = !empty($capa) ? upload_image_from_url($capa) : null;
    
    if (is_wp_error($cartaz_id) || is_wp_error($capa_id)) {
        return new WP_Error('upload_error', 'Erro ao fazer upload das imagens', ['status' => 500]);
    }

    // Cria o post
    $post_data = [
        'post_type' => 'filmes',
        'post_title' => $titulo,
        'post_status' => 'publish',
        'post_content' => $descricao
    ];

    $post_id = wp_insert_post($post_data);

    if (is_wp_error($post_id)) {
        return $post_id;
    }

    // Define imagem destacada
    if ($cartaz_id) {
        set_post_thumbnail($post_id, $cartaz_id);
    }

    // Prepara dados para o CFS
    $field_data = [
        'titulo_original' => $titulo_original,
        'trailer' => $trailer,
        'cartaz' => $cartaz_id ? wp_get_attachment_url($cartaz_id) : '',
        'capa' => $capa_id ? wp_get_attachment_url($capa_id) : '',
        'estreia' => $estreia,
        'duracao_minutos' => $duracao_minutos,
        'direcao' => $direcao,
        'roteiro' => $roteiro,
        'elenco' => $elenco,
        'fotos' => $fotos,
        'distribuicao' => $distribuicao_ids,
        'paises' => $paises_ids,
        'generos' => $genero_ids,
        'classificacao' => $classificacao_ids,
        'tecnologias' => $tecnologia_ids,
        'feriados' => $feriado_ids,
    ];

    // Salva os campos customizados
    CFS()->save($field_data, ['ID' => $post_id]);

    return rest_ensure_response([
        'message' => 'Filme criado com sucesso!',
        'post_id' => $post_id,
    ]);
}

/**
 * Funções auxiliares para processamento de arrays
 */
function processar_pessoas($pessoas) {
    $resultado = [];
    
    if (!is_array($pessoas)) {
        return $resultado;
    }

    foreach ($pessoas as $pessoa) {
        $item = [
            'nome' => sanitize_text_field($pessoa['nome'] ?? ''),
            'foto' => ''
        ];

        if (!empty($pessoa['foto'])) {
            $foto_id = upload_image_from_url($pessoa['foto']);
            if (!is_wp_error($foto_id)) {
                $item['foto'] = wp_get_attachment_url($foto_id);
            }
        }

        $resultado[] = $item;
    }

    return $resultado;
}

function processar_fotos($fotos) {
    $resultado = [];
    
    if (!is_array($fotos)) {
        return $resultado;
    }

    foreach ($fotos as $foto) {
        if (!empty($foto['foto'])) {
            $foto_id = upload_image_from_url($foto['foto']);
            if (!is_wp_error($foto_id)) {
                $resultado[] = ['foto' => wp_get_attachment_url($foto_id)];
            }
        }
    }

    return $resultado;
}

/**
 * Registra os endpoints da API
 */
function register_filmes_api_endpoints() {
    register_rest_route('api/v1', '/filmes', [
        [
            'methods' => 'GET',
            'callback' => 'api_filmes_get',
            'permission_callback' => '__return_true',
        ],
        [
            'methods' => 'POST',
            'callback' => 'api_filmes_post',
            'permission_callback' => function() {
                return current_user_can('edit_posts');
            },
        ]
    ]);

    register_rest_route('api/v1', '/ano-filmes', [
        'methods' => 'GET',
        'callback' => 'api_anos_filmes_get',
        'permission_callback' => '__return_true',
    ]);
}
add_action('rest_api_init', 'register_filmes_api_endpoints');
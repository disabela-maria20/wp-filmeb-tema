<?php

add_action('init', 'registrar_taxonomias_filmes', 5);
function registrar_taxonomias_filmes() {
    $taxonomias = [
        'distribuidoras' => 'Distribuidoras',
        'paises' => 'Países',
        'generos' => 'Gêneros',
        'classificacoes' => 'Classificações',
        'tecnologias' => 'Tecnologias',
    ];

    foreach ($taxonomias as $slug => $nome) {
        if (!taxonomy_exists($slug)) {
            register_taxonomy($slug, 'filmes', [
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
            ]);
        }
    }
}


function registrar_cpt_filmes()
{
    register_post_type('filmes', array(
        'labels' => array(
            'name' => _x('Filmes', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Filme', 'Post type singular name', 'textdomain'),
            'add_new_item' => __('Adicionar Novo Filme', 'textdomain'),
            'edit_item' => __('Editar Filme', 'textdomain'),
            'new_item' => __('Novo Filme', 'textdomain'),
            'view_item' => __('Ver Filme', 'textdomain'),
            'search_items' => __('Buscar Filmes', 'textdomain'),
            'not_found' => __('Nenhum Filme encontrado', 'textdomain'),
            'not_found_in_trash' => __('Nenhum Filme encontrado na lixeira', 'textdomain'),
        ),
        'description' => 'Gerenciar Filmes',
        'public' => true,
        'show_ui' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-video-alt',
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'filmes', 'with_front' => true),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'has_archive' => true,
        'publicly_queryable' => true,
        'show_in_rest' => true,
        'taxonomies' => array('distribuidoras', 'paises', 'generos', 'classificacoes', 'tecnologias', 'feriados', 'post_tag'),
    ));
}
add_action('init', 'registrar_cpt_filmes');

function filme_scheme($post)
{
    $filme = new stdClass();
    $filme->title = get_the_title($post);
    $filme->link = get_permalink($post);
    $filme->descricao = get_the_content(null, false, $post);
    $filme->titulo_original = cfs()->get('titulo_original', $post->ID);
    $filme->trailer = esc_url(cfs()->get('trailer', $post->ID));
    $filme->cartaz = esc_url(cfs()->get('cartaz', $post->ID));
    $filme->capa = esc_url(cfs()->get('capa', $post->ID));

    $fotos = cfs()->get('fotos', $post->ID);

    $filme->fotos = [];

    if ($fotos) {
        foreach ($fotos as $foto) {
            $filme->fotos[] = [
                'foto' => esc_url($foto['foto'] ?? ''),
            ];
        }
    }

    $filme->direcao = sanitize_text_field(cfs()->get('direcao', $post->ID));
    $filme->roteiro = sanitize_text_field(cfs()->get('roteiro', $post->ID));
    $filme->elenco = sanitize_text_field(cfs()->get('elenco', $post->ID));
    $filme->estreia = sanitize_text_field(cfs()->get('estreia', $post->ID));
    $filme->duracao_minutos = intval(cfs()->get('duracao_minutos', $post->ID));


    $data_estreia = $filme->estreia;
    if (!empty($data_estreia)) {
        $ano_estreia = date('Y', strtotime($data_estreia));
        $mes_estreia = date('F', strtotime($data_estreia));

        $filme->ano = (int) $ano_estreia;
        $filme->mes = $mes_estreia;
    } else {

        $filme->ano = null;
        $filme->mes = null;
    }


    $distribuidoras = cfs()->get('distribuicao', $post->ID);
    $filme->distribuidoras = [];
    if (is_array($distribuidoras)) {
        foreach ($distribuidoras as $distribuidora) {
            $term = get_term($distribuidora);
            if ($term && !is_wp_error($term)) {
                $filme->distribuidoras[] = $term->name;
            }
        }
    }

    $paises = cfs()->get('paises', $post->ID);
    $filme->paises = [];
    if (is_array($paises)) {
        foreach ($paises as $pais) {
            $term = get_term($pais);
            if ($term && !is_wp_error($term)) {
                $filme->paises[] = $term->name;
            }
        }
    }

    $generos = cfs()->get('generos', $post->ID);
    $filme->generos = [];
    if (is_array($generos)) {
        foreach ($generos as $genero) {
            $term = get_term($genero);
            if ($term && !is_wp_error($term)) {
                $filme->generos[] = $term->name;
            }
        }
    }

    $classificacoes = cfs()->get('classificacao', $post->ID);
    $filme->classificacoes = [];
    if (is_array($classificacoes)) {
        foreach ($classificacoes as $classificacao) {
            $term = get_term($classificacao);
            if ($term && !is_wp_error($term)) {
                $filme->classificacoes[] = $term->name;
            }
        }
    }

    $tecnologias = cfs()->get('tecnologias', $post->ID);
    $filme->tecnologias = [];
    if (is_array($tecnologias)) {
        foreach ($tecnologias as $tecnologia) {
            $term = get_term($tecnologia);
            if ($term && !is_wp_error($term)) {
                $filme->tecnologias[] = $term->name;
            }
        }
    }

    return $filme;
}

function api_filmes_get($request)
{
    $q = isset($request['q']) ? sanitize_text_field($request['q']) : '';
    $ano = isset($request['ano']) ? sanitize_text_field($request['ano']) : '';

    $query = array(
        'post_type' => 'filmes',
        's' => $q,
        'nopaging' => true,
    );

    if (!empty($ano)) {
        $query['meta_query'][] = array(
            'key' => 'estreia',
            'value' => $ano,
            'compare' => '=',
            'type' => 'NUMERIC',
        );
    }

    $loop = new WP_Query($query);
    $filmes = [];

    foreach ($loop->posts as $post) {
        $filmes[] = filme_scheme($post);
    }

    return rest_ensure_response($filmes);
}

function api_anos_filmes_get()
{
    $query = array(
        'post_type' => 'filmes',
        'posts_per_page' => -1,
        'fields' => 'ids'
    );

    $loop = new WP_Query($query);
    $posts = $loop->posts;

    $anos_filmes = [];

    foreach ($posts as $post_id) {
        $data_estreia = get_post_meta($post_id, 'estreia', true);

        if (!empty($data_estreia)) {
            $ano = date('Y', strtotime($data_estreia));

            if (!in_array($ano, $anos_filmes)) {
                $anos_filmes[] = $ano;
            }
        }
    }

    rsort($anos_filmes);

    return rest_ensure_response($anos_filmes);
}

function api_filmes_post($request) {
    $data = $request->get_json_params();

    $titulo = isset($data['titulo']) ? sanitize_text_field($data['titulo']) : '';
    $descricao = isset($data['descricao']) ? sanitize_textarea_field($data['descricao']) : '';
    $titulo_original = isset($data['titulo_original']) ? sanitize_text_field($data['titulo_original']) : '';
    $trailer = isset($data['trailer']) ? esc_url_raw($data['trailer']) : '';
    $cartaz = isset($data['cartaz']) ? esc_url_raw($data['cartaz']) : '';
    $capa = isset($data['capa']) ? esc_url_raw($data['capa']) : '';
    $estreia = isset($data['estreia']) ? sanitize_text_field($data['estreia']) : '';
    $duracao_minutos = isset($data['duracao_minutos']) ? intval($data['duracao_minutos']) : 0;
    
    // Tratamento dos arrays de objetos
    $direcao = isset($data['direcao']) && is_array($data['direcao']) 
        ? array_map(function($item) {
            return [
                'nome' => sanitize_text_field($item['nome'] ?? ''),
                'foto' => esc_url_raw($item['foto'] ?? '')
            ];
        }, $data['direcao']) 
        : [];

    $roteiro = isset($data['roteiro']) && is_array($data['roteiro'])
        ? array_map(function($item) {
            return [
                'nome' => sanitize_text_field($item['nome'] ?? ''),
                'foto' => esc_url_raw($item['foto'] ?? '')
            ];
        }, $data['roteiro']) 
        : [];

    $elenco = isset($data['elenco']) && is_array($data['elenco'])
        ? array_map(function($item) {
            return [
                'nome' => sanitize_text_field($item['nome'] ?? ''),
                'foto' => esc_url_raw($item['foto'] ?? '')
            ];
        }, $data['elenco']) 
        : [];

    // Ajuste para obter corretamente as URLs das fotos
    $fotos = isset($data['fotos']) && is_array($data['fotos'])
        ? array_map(fn($foto) => ['foto' => esc_url_raw($foto['foto'] ?? '')], $data['fotos'])
        : [];

    $distribuicao = isset($data['distribuicao']) ? ((array) $data['distribuicao']) : [];
    $paises = isset($data['paises']) ? ((array) $data['paises']) : [];
    $generos = isset($data['generos']) ? ((array) $data['generos']) : [];
    $classificacao = isset($data['classificacoes']) ? ((array) $data['classificacoes']) : [];
    $tecnologias = isset($data['tecnologias']) ? ((array) $data['tecnologias']) : [];
    $feriados = isset($data['feriados']) ? ((array) $data['feriados']) : [];



    $distribuicao_ids = obter_term_ids($distribuicao, 'distribuidoras');
    $paises_ids = obter_term_ids($paises, 'paises');
    $genero_ids = obter_term_ids($generos, 'generos');
    $classificacao_ids = obter_term_ids($classificacao, 'classificacoes');
    $tecnologia_ids = obter_term_ids($tecnologias, 'tecnologias');
    $feriado_ids = obter_term_ids($feriados, 'feriados');

    // Upload de imagens - somente se a URL não estiver vazia
    $cartaz_id = null;
    if (!empty($cartaz)) {
        $cartaz_id = upload_image_from_url($cartaz);
        if (is_wp_error($cartaz_id)) {
            return new WP_Error('upload_error', 'Erro ao baixar a imagem do cartaz.', ['status' => 500, 'data' => $cartaz]);
        }
    }

    $capa_id = null;
    if (!empty($capa)) {
        $capa_id = upload_image_from_url($capa);
        if (is_wp_error($capa_id)) {
            return new WP_Error('upload_error', 'Erro ao baixar a imagem da capa.', ['status' => 500, 'data' => $capa]);
        }
    }

    // Upload fotos de direção, roteiro e elenco
    foreach ($direcao as &$diretor) {
        if (!empty($diretor['foto'])) {
            $foto_id = upload_image_from_url($diretor['foto']);
            if (!is_wp_error($foto_id)) {
                $diretor['foto'] = wp_get_attachment_url($foto_id);
            }
        }
    }

    foreach ($roteiro as &$roteirista) {
        if (!empty($roteirista['foto'])) {
            $foto_id = upload_image_from_url($roteirista['foto']);
            if (!is_wp_error($foto_id)) {
                $roteirista['foto'] = wp_get_attachment_url($foto_id);
            }
        }
    }

    foreach ($elenco as &$ator) {
        if (!empty($ator['foto'])) {
            $foto_id = upload_image_from_url($ator['foto']);
            if (!is_wp_error($foto_id)) {
                $ator['foto'] = wp_get_attachment_url($foto_id);
            }
        }
    }

    $fotos_id = [];
    foreach ($fotos as $foto) {
        if (!empty($foto['foto'])) {
            $foto_id = upload_image_from_url($foto['foto']);
            if (!is_wp_error($foto_id)) {
                $fotos_id[] = $foto_id;
            }
        }
    }

    $post_data = [
        'post_type' => 'filmes',
        'post_title' => $titulo,
        'post_status' => 'publish',
        'post_content' => $descricao
    ];

    $post_id = wp_insert_post($post_data);
  
    if ($post_id) {
        // Define a imagem destacada somente se o cartaz foi enviado
        if ($cartaz_id) {
            set_post_thumbnail($post_id, $cartaz_id);
        }

        $fotos_formatadas = array_map(fn($foto_id) => ['foto' => wp_get_attachment_url($foto_id)], $fotos_id);

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
            'fotos' => $fotos_formatadas,
            'distribuicao' => $distribuicao_ids,
            'paises' => $paises_ids,
            'generos' => $genero_ids,
            'classificacao' => $classificacao_ids,
            'tecnologias' => $tecnologia_ids,
            'feriados' => $feriado_ids ?? null
        ];

        CFS()->save($field_data, ['ID' => $post_id]);

        return rest_ensure_response([
            'message' => 'Filme criado com sucesso!',
            'post_id' => $post_id,
        ]);
    }

    return new WP_Error('filme_nao_criado', 'Erro ao criar o filme', ['status' => 500]);
}

function register_filmes_api_endpoints()
{
    register_rest_route('api/v1', '/filmes', [
        'methods' => 'GET',
        'callback' => 'api_filmes_get',
    ]);

    register_rest_route('api/v1', '/filmes', [
        'methods' => 'POST',
        'callback' => 'api_filmes_post',
    ]);

    register_rest_route('api/v1', '/ano-filmes', array(
        'methods' => 'GET',
        'callback' => 'api_anos_filmes_get',

    ));
}
add_action('rest_api_init', 'register_filmes_api_endpoints');
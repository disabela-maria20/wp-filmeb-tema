<?php
add_action('init', 'registrar_taxonomias_filmes');
function registrar_taxonomias_filmes()
{
    $taxonomias = [
        'distribuidoras' => 'Distribuidoras',
        'paises'         => 'Países',
        'generos'        => 'Gêneros',
        'classificacoes' => 'Classificações',
        'tecnologias'    => 'Tecnologias',
        'feriados'       => 'Feriados',
    ];

    foreach ($taxonomias as $slug => $nome) {
        register_taxonomy(
            $slug,
            'filmes',
            array(
                'labels' => array(
                    'name'              => $nome,
                    'singular_name'     => $nome,
                    'search_items'      => "Buscar $nome",
                    'all_items'         => "Todos os $nome",
                    'edit_item'         => "Editar $nome",
                    'update_item'       => "Atualizar $nome",
                    'add_new_item'      => "Adicionar Novo $nome",
                    'new_item_name'     => "Novo $nome",
                    'menu_name'         => $nome,
                ),
                'hierarchical' => true,
                'show_ui'      => true,
                'show_in_menu' => true,
                'show_in_rest' => true,
                'rewrite'      => array('slug' => $slug),
            )
        );
    }
}

function filme_scheme($post)
{
    $filme = new stdClass();
    $filme->title = get_the_title($post);
    $filme->link = get_permalink($post);
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

        $filme->ano = (int)$ano_estreia;
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


    $feriados = cfs()->get('feriados', $post->ID);
    $filme->feriados = [];
    if (is_array($feriados)) {
        foreach ($feriados as $feriado) {
            $term = get_term($feriado);
            if ($term && !is_wp_error($term)) {
                $filme->feriados[] = $term->name;
            }
        }
    }
    return $filme;
}

function api_filmes_get($request)
{
    $q = isset($request['q']) ? sanitize_text_field($request['q']) : '';
    $ano = isset($request['ano']) ? sanitize_text_field($request['ano']) : '';
    $page = isset($request['page']) ? sanitize_text_field($request['page']) : 1;
    $limit = isset($request['limit']) ? sanitize_text_field($request['limit']) : 20;

    // Filtros adicionais
    $distribuicao = isset($request['distribuicao']) ? $request['distribuicao'] : '';
    $paises = isset($request['paises']) ? $request['paises'] : '';
    $generos = isset($request['generos']) ? $request['generos'] : '';
    $classificacoes = isset($request['classificacoes']) ? $request['classificacoes'] : '';
    $tecnologias = isset($request['tecnologias']) ? $request['tecnologias'] : '';
    $feriados = isset($request['feriados']) ? $request['feriados'] : '';

    $query = array(
        'post_type' => 'filmes',
        'posts_per_page' => $limit,
        'paged' => $page,
        's' => $q,
    );

    // Filtro de ano
    if ($ano) {
        $query['meta_query'][] = array(
            'key' => 'estreia',
            'value' => $ano,
            'compare' => 'LIKE',
            'type' => 'CHAR'
        );
    }

    // Filtros de taxonomias (somente se fornecidos)
    $tax_query = [];

    if ($distribuicao) {
        $tax_query[] = array(
            'taxonomy' => 'distribuicao',
            'field' => 'id',
            'terms' => $distribuicao,
            'operator' => 'IN',
        );
    }

    if ($paises) {
        $tax_query[] = array(
            'taxonomy' => 'paises',
            'field' => 'id',
            'terms' => $paises,
            'operator' => 'IN',
        );
    }

    if ($generos) {
        $tax_query[] = array(
            'taxonomy' => 'generos',
            'field' => 'id',
            'terms' => $generos,
            'operator' => 'IN',
        );
    }

    if ($classificacoes) {
        $tax_query[] = array(
            'taxonomy' => 'classificacao',
            'field' => 'id',
            'terms' => $classificacoes,
            'operator' => 'IN',
        );
    }

    if ($tecnologias) {
        $tax_query[] = array(
            'taxonomy' => 'tecnologias',
            'field' => 'id',
            'terms' => $tecnologias,
            'operator' => 'IN',
        );
    }

    if ($feriados) {
        $tax_query[] = array(
            'taxonomy' => 'feriados',
            'field' => 'id',
            'terms' => $feriados,
            'operator' => 'IN',
        );
    }

    // Se houver filtros de taxonomia, adiciona ao query
    if (!empty($tax_query)) {
        $query['tax_query'] = $tax_query;
    }

    // Realiza a consulta
    $loop = new WP_Query($query);
    $posts = $loop->posts;
    $total = $loop->found_posts;

    $filmes = [];

    foreach ($posts as $post) {
        $filme = filme_scheme($post);
        $filmes[] = $filme;
    }

    usort($filmes, function ($a, $b) {
        return $b->ano - $a->ano;
    });

    $response = rest_ensure_response($filmes);
    $response->header('X-Total-Count', $total);
    return $response;
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

function obter_term_ids($itens, $taxonomia)
{
    $ids = [];

    foreach ($itens as $item) {
        if (is_numeric($item)) {

            $ids[] = intval($item);
        } else {

            $term = get_term_by('name', $item, $taxonomia);

            if ($term && !is_wp_error($term)) {

                $ids[] = $term->term_id;
            } else {

                error_log('Termo não encontrado para ' . $taxonomia . ': ' . $item);
            }
        }
    }

    return $ids;
}

function api_filmes_post($request)
{
    $data = $request->get_json_params();

    $titulo = isset($data['titulo']) ? sanitize_text_field($data['titulo']) : '';
    $titulo_original = isset($data['titulo_original']) ? sanitize_text_field($data['titulo_original']) : '';
    $trailer = isset($data['trailer']) ? esc_url_raw($data['trailer']) : '';
    $cartaz = isset($data['cartaz']) ? esc_url_raw($data['cartaz']) : '';
    $capa = isset($data['capa']) ? esc_url_raw($data['capa']) : '';
    $estreia = isset($data['estreia']) ? sanitize_text_field($data['estreia']) : '';
    $duracao_minutos = isset($data['duracao_minutos']) ? intval($data['duracao_minutos']) : 0;
    $direcao = isset($data['direcao']) ? sanitize_text_field($data['direcao']) : '';
    $roteiro = isset($data['roteiro']) ? sanitize_text_field($data['roteiro']) : '';
    $elenco = isset($data['elenco']) ? sanitize_text_field($data['elenco']) : '';
    $fotos = isset($data['fotos']) ? $data['fotos'] : [];
    $distribuicao = isset($data['distribuicao']) ? (array) $data['distribuicao'] : [];
    $paises = isset($data['paises']) ? (array) $data['paises'] : [];

    $generos = isset($data['generos']) ? (array) $data['generos'] : [];
    $classificacoes = isset($data['classificacao']) ? (array) $data['classificacao'] : [];
    $tecnologias = isset($data['tecnologias']) ? (array) $data['tecnologias'] : [];
    $feriados = isset($data['feriados']) ? (array) $data['paisesferiados'] : [];

    $distribuicao_ids = obter_term_ids($distribuicao, 'distribuidoras');
    $paises_ids = obter_term_ids($paises, 'paises');
    $genero_ids = obter_term_ids($generos, 'generos');
    $classificacao_ids = obter_term_ids($classificacoes, 'classificacoes');
    $tecnologia_ids = obter_term_ids($tecnologias, 'tecnologias');
    $feriado_ids = obter_term_ids($feriados, 'feriados');



    $post_data = [
        'post_type'    => 'filmes',
        'post_title'   => $titulo,
        'post_status'  => 'publish',
        'post_content' => '',
    ];

    $post_id = wp_insert_post($post_data);

    if ($post_id) {

        $field_data = [
            'titulo_original' => $titulo_original,
            'trailer' => $trailer,
            'cartaz' => $cartaz,
            'capa' => $capa,
            'estreia' => $estreia,
            'duracao_minutos' => $duracao_minutos,
            'direcao' => $direcao,
            'roteiro' => $roteiro,
            'elenco' => $elenco,
            'fotos' => $fotos,
            'distribuicao' => $distribuicao_ids,
            'paises' => $paises_ids,
            'generos' => $genero_ids,
            'classificacoes' => $classificacao_ids,
            'tecnologias' => $tecnologia_ids,
            'feriados' => $feriado_ids,
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

    register_rest_route('api/v1', '/anos-filmes', [
        'methods' => 'GET',
        'callback' => 'api_anos_filmes_get'
    ]);
}
add_action('rest_api_init', 'register_filmes_api_endpoints');

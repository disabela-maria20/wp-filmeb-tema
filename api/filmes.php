<?php
// Registrar Custom Post Type "Filmes"
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
                'hierarchical' => true, // True para categorias, False para tags
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
    $filme->title =  get_the_title($post);
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

    // Distribuidoras
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

    // Países
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

    // Gêneros
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

    // Classificações
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

    // Tecnologias
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

    // Feriados
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
    $ano = isset($request['ano']) ? sanitize_text_field($request['ano']) : ''; // Captura o parâmetro "ano"
    $page = isset($request['page']) ? sanitize_text_field($request['page']) : 1;
    $limit = isset($request['limit']) ? sanitize_text_field($request['limit']) : 20;

    // Configura a consulta
    $query = array(
        'post_type' => 'filmes',
        'posts_per_page' => $limit,
        'paged' => $page,
        's' => $q,
    );

    // Se o parâmetro "ano" for fornecido, adiciona à consulta
    if ($ano) {
        $query['meta_query'] = array(
            array(
                'key' => 'estreia', // Ou o campo de data que você usa para armazenar o ano
                'value' => $ano,
                'compare' => 'LIKE', // O 'LIKE' pode ser utilizado para verificar o ano na data
                'type' => 'CHAR'
            )
        );
    }

    $loop = new WP_Query($query);
    $posts = $loop->posts;
    $total = $loop->found_posts;

    $filmes_agrupados = [];

    foreach ($posts as $post) {
        $filme = filme_scheme($post);
        $data_estreia = $filme->estreia; 

        if (!empty($data_estreia)) {
            $ano_filme = date('Y', strtotime($data_estreia));

            if (!isset($filmes_agrupados[$ano_filme])) {
                $filmes_agrupados[$ano_filme] = [
                    'year' => $ano_filme,
                    'months' => []
                ];
            }

            $mes = date('F', strtotime($data_estreia)); 
            $mes_existe = false;

            foreach ($filmes_agrupados[$ano_filme]['months'] as &$item) {
                if ($item['month'] === $mes) {
                    $item['movies'][] = $filme;
                    $mes_existe = true;
                    break;
                }
            }

            if (!$mes_existe) {
                $filmes_agrupados[$ano_filme]['months'][] = [
                    'month' => $mes,
                    'movies' => [$filme]
                ];
            }
        } else {
            if (!isset($filmes_agrupados['sem_data'])) {
                $filmes_agrupados['sem_data'] = [
                    'year' => 'sem_data',
                    'movies' => []
                ];
            }
            $filmes_agrupados['sem_data']['movies'][] = $filme;
        }
    }

    // Organiza os filmes por ano
    $result = array_values($filmes_agrupados); // Converte os anos para um array contínuo
    usort($result, function ($a, $b) {
        return $b['year'] - $a['year']; // Ordena os anos de forma decrescente
    });

    // Ordena os meses dentro de cada ano
    foreach ($result as &$ano_filmes) {
        usort($ano_filmes['months'], function ($a, $b) {
            return strcmp($a['month'], $b['month']);
        });
    }

    // Resposta com a estrutura desejada
    $response = rest_ensure_response($result);
    $response->header('X-Total-Count', $total);
    return $response;
}

function register_filmes_api_endpoints()
{
    register_rest_route('api/v1', '/filmes', [
        'methods' => 'GET',
        'callback' => 'api_filmes_get'
    ]);
}
add_action('rest_api_init', 'register_filmes_api_endpoints');


function api_anos_filmes_get($request)
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

function register_anos_filmes_api_endpoints()
{
    register_rest_route('api/v1', '/anos-filmes', [
        'methods' => 'GET',
        'callback' => 'api_anos_filmes_get'
    ]);
}
add_action('rest_api_init', 'register_anos_filmes_api_endpoints');

?>
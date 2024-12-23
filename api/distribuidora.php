<?php

function api_distribuidora_get($request)
{
    $page = isset($request['page']) ? intval($request['page']) : 1;
    $limit = isset($request['limit']) ? intval($request['limit']) : 10;

    // Configuração da consulta
    $query = array(
        'post_type' => 'filmes',
        'posts_per_page' => -1, // Obter todos os posts inicialmente
        'meta_key' => 'estreia',
        'orderby' => 'meta_value',
        'order' => 'ASC',
    );

    $loop = new WP_Query($query);
    $posts = $loop->posts;
    $total = $loop->found_posts;

    $resultData = [];

    foreach ($posts as $post) {
        $filme = filme_scheme($post);
        $estreia = $filme->estreia;

        // Calcular ano e nome do mês da estreia
        $dataEstreia = new DateTime($estreia);
        $ano = $dataEstreia->format('Y');
        $mes = $dataEstreia->format('F');

        // Distribuidores fixos
        $distribuidoresBase = [
            'Disney' => [],
            'Paramount' => [],
            'Sony' => [],
            'Universal' => [],
            'Warner' => [],
            'downtownParis' => [],
            'Imagem' => [],
            'Paris' => [],
            'Diamond' => [],
            'OutrasDistribuidoras' => []
        ];

        // Verificar distribuidora principal
        $distribuidora = $filme->distribuidoras[0] ?? 'OutrasDistribuidoras';
        $distribuidorasMap = [
            'Diamond/Galeria' => 'Diamond',
            'Disney' => 'Disney',
            'Paramount' => 'Paramount',
            'Sony' => 'Sony',
            'Universal' => 'Universal',
            'Warner' => 'Warner',
            'downtownParis' => 'downtownParis',
            'Paris' => 'Paris'
        ];
        $distribuidora = $distribuidorasMap[$distribuidora] ?? 'OutrasDistribuidoras';

        // Dados individuais do filme
        $filmeData = [
            'link' => $filme->link,
            'title' => $filme->title,
            'titulo_original' => $filme->titulo_original
        ];

        // Verificar se já existe um item com a mesma data de estreia
        $found = false;
        foreach ($resultData as &$dataItem) {
            if ($dataItem['estreia'] === $estreia) {
                $dataItem[$distribuidora][] = $filmeData;
                $found = true;
                break;
            }
        }

        // Criar novo item se não existir
        if (!$found) {
            $resultData[] = array_merge([
                'estreia' => $estreia,
                'ano' => $ano,
                'mes' => $mes,
                'distribuidoras' => $filme->distribuidoras,
                'origem' => $filme->paises,
                'genero' => $filme->generos,
                'tecnologia' => $filme->tecnologias
            ], $distribuidoresBase, [
                $distribuidora => [$filmeData]
            ]);
        }
    }

    // Paginação manual
    $offset = ($page - 1) * $limit;
    $pagedData = array_slice($resultData, $offset, $limit);

    // Resposta final
    $response = rest_ensure_response([
        'data' => $pagedData,
        'total' => $total,
        'page' => $page,
        'total_pages' => ceil($total / $limit),
    ]);
    $response->header('X-Total-Count', $total);
    return $response;
}

function register_distribuidora_api_endpoints()
{
    register_rest_route('api/v1', '/distribuidoras', [
        'methods' => 'GET',
        'callback' => 'api_distribuidora_get',
        'args' => [
            'page' => [
                'required' => false,
                'default' => 1,
                'validate_callback' => function ($param) {
                    return is_numeric($param) && $param > 0;
                },
            ],
            'limit' => [
                'required' => false,
                'default' => 10,
                'validate_callback' => function ($param) {
                    return is_numeric($param) && $param > 0;
                },
            ],
        ],
    ]);
}
add_action('rest_api_init', 'register_distribuidora_api_endpoints');
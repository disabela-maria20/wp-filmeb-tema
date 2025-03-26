<?php

function api_distribuidora_get($request)
{
    // Configuração da consulta
    $query = array(
        'post_type' => 'filmes',
        'posts_per_page' => -1, // Obter todos os posts
        'meta_key' => 'estreia',
        'orderby' => 'meta_value',
        'order' => 'DESC',
    );

    $loop = new WP_Query($query);
    $posts = $loop->posts;

    $resultData = [];

    foreach ($posts as $post) {
        $filme = filme_scheme($post);
        $estreia = $filme->estreia ?? null;

        // Verifica se a estreia é válida antes de criar um DateTime
        if (!$estreia) {
            continue;
        }

        try {
            $dataEstreia = new DateTime($estreia);
            $ano = $dataEstreia->format('Y');
            $mes = $dataEstreia->format('F');
        } catch (Exception $e) {
            continue; // Se houver erro na data, ignora esse filme
        }

        // Distribuidores fixos
        $distribuidoresBase = [
            'Disney' => [],
            'Paramount' => [],
            'Sony' => [],
            'Universal' => [],
            'Warner' => [],
            'downtownParis' => [],
            'Imagem Filmes' => [],
            'Paris' => [],
            'Diamond' => [],
            'OutrasDistribuidoras' => []
        ];

        $distribuidora = $filme->distribuidoras[0] ?? 'OutrasDistribuidoras';
        $distribuidorasMap = [
            'Diamond/Galeria' => 'Diamond',
            'Disney' => 'Disney',
            'Paramount' => 'Paramount',
            'Sony' => 'Sony',
            'Universal' => 'Universal',
            'Warner' => 'Warner',
            'downtownParis' => 'downtownParis',
            'Paris' => 'Paris',
            'Imagem' => 'Imagem Filmes' // Adicionado para completar o mapeamento
        ];
        $distribuidora = $distribuidorasMap[$distribuidora] ?? 'OutrasDistribuidoras';

        // Dados individuais do filme
        $filmeData = [
            'link' => $filme->link ?? '',
            'title' => $filme->title ?? '',
            'titulo_original' => $filme->titulo_original ?? ''
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
                'distribuidoras' => $filme->distribuidoras ?? [],
                'origem' => $filme->paises ?? [],
                'genero' => $filme->generos ?? [],
                'tecnologia' => $filme->tecnologias ?? []
            ], $distribuidoresBase, [
                $distribuidora => [$filmeData]
            ]);
        }
    }

    // Resposta final sem paginação
    $response = rest_ensure_response([
        'data' => $resultData,
        'total' => count($resultData),
    ]);

    return $response;
}

function register_distribuidora_api_endpoints()
{
    register_rest_route('api/v1', '/distribuidoras', [
        'methods' => 'GET',
        'callback' => 'api_distribuidora_get',
    ]);
}
add_action('rest_api_init', 'register_distribuidora_api_endpoints');
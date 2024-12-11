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

?>
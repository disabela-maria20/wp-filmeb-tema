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
        'has_archive'=> true,
    ));
}
add_action('init', 'registrar_cpt_rapidinhas');


function registrar_cpt_boletim_da_semana()
{
    register_post_type('boletim_da_semana', array(
        'labels' => array(
            'name' => _x('Boletins da Semana', 'Post type general name', 'textdomain'),
            'singular_name' => _x('Boletim da Semana', 'Post type singular name', 'textdomain'),
            'add_new_item' => _x('Adicionar Novo Boletim', 'Post type singular name', 'textdomain'),
            'new_item' => _x('Novo Boletim', 'Post type singular name', 'textdomain'),
            'edit_item' => _x('Editar Boletim', 'Post type singular name', 'textdomain'),
            'view_item' => _x('Ver Boletim', 'Post type singular name', 'textdomain'),
        ),
        'description' => 'Gerenciar Boletins da Semana',
        'public' => true,
        'show_ui' => true,
        'capability_type' => 'post',
        'rewrite' => array('slug' => 'boletim-da-semana', 'with_front' => true),
        'query_var' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'publicly_queryable' => true,
        'has_archive'=> true,
    ));
}
add_action('init', 'registrar_cpt_boletim_da_semana');


?>